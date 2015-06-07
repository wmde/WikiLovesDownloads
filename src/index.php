<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once "../vendor/autoload.php";

include 'config.php';
include 'WikiLovesDownloads.php';

if ( !defined( 'API_URL' ) ) {
	print "API service URL not defined.";
	exit( 1 );
}

if ( !defined( 'DOWNLOAD_CATEGORY' ) ) {
	print "Download category not defined";
	exit( 1 );
}

$wld = new WikiLovesDownloads( $userFilter );

if ( defined( 'API_USER' ) && defined( 'API_PASSWORD' ) ) {
	$wld->doApiLogin( API_USER, API_PASSWORD );
}
$wld->loadCategoryMembers( DOWNLOAD_CATEGORY );
$wld->processImages();

$urlLists = $wld->getUrls( count( $userFilter ) );
foreach ( $urlLists as $key => $urls ) {
	$content = implode( "\n", $urls );
	$filename = '../' . $wld->getListFilename( $key, strlen( count( $userFilter ) ) );
	file_put_contents( $filename, $content );
}
echo $wld->getNumImagesByFilteredUsers() . " pages pages were filtered by user.\n";
