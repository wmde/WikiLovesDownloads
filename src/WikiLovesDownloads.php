<?php
use Mediawiki\Api\ApiUser;
use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\MediawikiFactory;
use Mediawiki\Api\Options\ListCategoryMembersOptions;
use Mediawiki\Api\SimpleRequest;
use Mediawiki\DataModel\Page;
use Mediawiki\DataModel\Pages;

class WikiLovesDownloads {

	const NAMESPACE_FILE = 6;

	/** @var MediawikiApi instance of the api client */
	private $api = '';

	/** @var Pages collection of images as returned by wikipedia::categorymembers() */
	private $images = array();

	/** @var array array of lists of files */
	private $urls = array();

	/** @var true on successful login */
	private $loggedIn = false;

	/** @var array array of users to filter */
	private $userFilter = array();

	/** @var int number of images created by any filtered user */
	private $numImagesByFilteredUsers = 0;

	/** @var MediawikiFactory */
	private $services;

	/** @var int number of images for which the image info had been retrieved */
	private $numImageInfoRetrieved = 0;

	/**
	 * @param array $userFilter
	 * @param ListCategoryMembersOptions $options
	 */
	public function __construct( array $userFilter = null, ListCategoryMembersOptions $options = null ) {
		$this->api = new MediawikiApi( API_URL );
		$this->services = new MediawikiFactory( $this->api );

		$this->options = $options ?: new ListCategoryMembersOptions();
		$this->userFilter = $userFilter;
	}

	/**
	 * Performs a login into the API
	 * @param $username
	 * @param $password
	 */
	public function doApiLogin( $username, $password ) {
		$this->loggedIn = $this->api->login( new ApiUser( $username, $password ) );
	}

	/**
	 * @param string $topCategory of the category to download files from
	 * @return bool
	 */
	public function loadCategoryMembers( $topCategory ) {
		$this->images = $this->services->newPageListGetter()->getPageListFromCategoryName(
			$topCategory,
			$this->options
		);

		# @TODO: extend mediawiki api to accept parameter cmtype 
		$this->images = $this->filterByNamespace( self::NAMESPACE_FILE );

		$images = array_keys( $this->images->toArray() );
		while ( true ) {
			$chunk = array_splice( $images, 0, 50 );
			if ( count( $chunk ) === 0 ) {
				return true;
			}
			$this->extendWithImageInfo( $chunk );
		}
	}

	/**
	 * iterate through the collection of pages and distribute the urls to a given number of lists
	 */
	public function processImages() {
		foreach ( $this->images->toArray() as $image ) {
			if ( !$this->isImageOfFilteredUser( $image ) ) {
				$this->urls[] = $image->imageInfo['url'];
			}
		}
	}

	public function getUrls( $numberOfLists = 1 ) {
		$downloadLists = array_fill( 0, $numberOfLists, array() );
		$currentListIndex = 0;
		foreach ( $this->urls as $url ) {
			$downloadLists[$currentListIndex][] = $url;

			$currentListIndex++;
			if ( $currentListIndex > $numberOfLists - 1 ) {
				$currentListIndex = 0;
			}
		}
		return $downloadLists;
	}

	/**
	 * Looks up the uploader of the file and returns true if the author should be filtered
	 * @param Page $image
	 * @return bool
	 */
	private function isImageOfFilteredUser( Page $image ) {
		if ( in_array( $image->imageInfo['user'], $this->userFilter ) ) {
			$this->numImagesByFilteredUsers++;
			return true;
		}
		return false;
	}

	/**
	 * @return int
	 */
	public function getNumImagesByFilteredUsers() {
		return $this->numImagesByFilteredUsers;
	}

	private function extendWithImageInfo( $chunk ) {
		# @todo extend wikimedia api to support files
		$imageInfo = $this->api->getRequest( new SimpleRequest(
			'query',
			array(
				'prop' => 'imageinfo',
				'iiprop' => 'url|user|timestamp',
				'pageids' => implode( '|', $chunk ),
			)
		) );

		# add image info to the page objects and re-add those to the collection
		foreach( $imageInfo['query']['pages'] as $pageId => $page ) {
			$pageObject = $this->images->get( $pageId );
			$pageObject->imageInfo = $page['imageinfo'][0];
			$this->images->addPage( $pageObject );
		}
		$this->numImageInfoRetrieved += count( $chunk );
	}

	/**
	 * @param int $namespaceId
	 * @return Pages
	 */
	private function filterByNamespace( $namespaceId ) {
		$filteredPages = new Pages();
		foreach ( $this->images->toArray() as $imagePage ) {
			if( $imagePage->getPageIdentifier()->getTitle()->getNs() === $namespaceId ) {
				$filteredPages->addPage( $imagePage );
			}
		}
		return $filteredPages;
	}

	public function getListFilename( $key, $numDigits ) {
		return ( defined( 'FILE_PREFIX' ) ? FILE_PREFIX : '' ) .
			str_pad( ( intval( $key ) + 1 ), $numDigits, '0', STR_PAD_LEFT ) .
			'.txt';
	}
}
