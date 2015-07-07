<?php

class ArchiveCreator {

	public function delete() {
		$writableDir = '../writable/';
		$zipDeletionList = scandir( $writableDir );

		foreach ( $zipDeletionList as $file ) {
			$currentTime = time();
			$fileTime = (int)filemtime( '../writable/' . $file ) + 3600;

			if ( $currentTime > $fileTime ) {
				if ( $file === '.' || $file === '..' ) {

				} else {
					unlink( '../writable/' . $file );
				}
			}
		}
	}

	public function create() {
		global $zip, $zipName, $zipPath, $tempPath;

		$stamp = uniqid();

		$tempPath = '../writable/temp' . $stamp;
		mkdir( $tempPath );

		$zip = new ZipArchive;
		$zipName = 'WLD-' . $stamp . '.zip';
		$zipPath = '../writable/' . $zipName;

		$zipCreate = fopen( $zipPath, 'w' );
		fclose( $zipCreate );
	}

	public function zip( $fileName, $content ) {
		global $zip, $zipPath, $tempPath;

		$filePath = $tempPath . $fileName;
		file_put_contents( $filePath, $content );
		$zip->open( $zipPath );
		$zip->addFile( $filePath, $fileName );
		$zip->close();
		unlink( $filePath );
	}

	public function finish() {
		global $tempPath, $zipPath, $zipName;

		rmdir( $tempPath );
		header( 'Content-Type: application/zip' );
		header( 'Content-Disposition: attachment; filename=' . $zipName );
		header( 'Content-Length: ' . filesize( $zipPath ) );
		header( 'Location: ' . $zipPath );
	}

}