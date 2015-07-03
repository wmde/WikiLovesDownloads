<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../vendor/autoload.php';

include 'config.php';
include 'WikiLovesDownloads.php';

if ( !defined( 'API_URL' ) ) {
	print 'API service URL not defined.';
	exit( 1 );
}

$category = $_POST[ 'category' ];
$listQuantity = $_POST[ 'listQuantity' ];
$userFilter = explode( ',' , $_POST[ 'names' ] );
$downloadCategory = "category:" . $category;

$wld = new WikiLovesDownloads( $userFilter );

if ( defined( 'API_USER' ) && defined( 'API_PASSWORD' ) ) {
	$wld->doApiLogin( API_USER, API_PASSWORD );
}
$wld->loadCategoryMembers( $downloadCategory );
$wld->processImages();

array_map( 'unlink', glob( '../*.zip' ) );

$date = new DateTime();
$stamp = $date->format( 'HisdmY' );

$tempPath = '../temp' . $stamp;
mkdir( $tempPath );

$zip = new ZipArchive;
$zipName = 'WLD-' . $stamp . '.zip';
$zipPath = '../' . $zipName;

$zipCreate = fopen( $zipPath, 'w' );
fclose( $zipCreate );

$urlLists = $wld->getUrls( $listQuantity );
foreach ( $urlLists as $key => $urls ) {
	$content = implode( "\n", $urls );
	$fileName = $wld->getListFilename( $key, 1 );
	$filePath = $tempPath . $fileName;
	file_put_contents( $filePath, $content );
	$zip->open( $zipPath );
	$zip->addFile( $filePath, $fileName );
	$zip->close();
	unlink( $filePath );
}

rmdir( $tempPath );

header( 'Content-Type: application/zip' );
header( 'Content-Disposition: attachment; filename=' . $zipName );
header( 'Content-Length: ' . filesize( $zipPath ) );
header( 'Location: ' . $zipPath );