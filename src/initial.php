<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../vendor/autoload.php';

include 'config.php';
include 'WikiLovesDownloads.php';
include 'creation.php';

if ( !defined( 'API_URL' ) ) {
	print 'API service URL not defined.';
	exit( 1 );
}

$category = $_POST[ 'category' ];
$listQuantity = $_POST[ 'listQuantity' ];
$userFilter = explode( ',' , $_POST[ 'names' ] );
$downloadCategory = "category:" . $category;

$wld = new WikiLovesDownloads( $userFilter );
$create = new creation();

if ( defined( 'API_USER' ) && defined( 'API_PASSWORD' ) ) {
	$wld->doApiLogin( API_USER, API_PASSWORD );
}

$wld->loadCategoryMembers( $downloadCategory );
$wld->processImages();

$create->delete();

$urlLists = $wld->getUrls( $listQuantity );

$create->create();

foreach ( $urlLists as $key => $urls ) {
	$content = implode( "\n", $urls );
	$fileName = $wld->getListFilename( $key, 1 );
	$create->zip( $fileName, $content );
}

$create->finish();


