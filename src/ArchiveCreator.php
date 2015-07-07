<?php

class ArchiveCreator {

	private $zipName;
	private $zipPath;
	private $tempPath;
	private $zip;

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
		$stamp = uniqid();

		$tempPath = '../writable/temp' . $stamp;
		mkdir( $tempPath );

		$this->zipName = 'WLD-' . $stamp . '.zip';
		$this->zipPath = '../writable/' . $this->zipName;

		$zipCreate = fopen( $this->zipPath, 'w' );
		fclose( $zipCreate );
	}

	public function zip( $fileName, $content ) {
		$filePath = $this->tempPath . $fileName;
		file_put_contents( $filePath, $content );
		$this->zip = new ZipArchive;
		$this->zip->open( $this->zipPath );
		$this->zip->addFile( $filePath, $fileName );
		$this->zip->close();
		unlink( $filePath );
	}

	public function finish() {
		rmdir( $this->tempPath );
		header( 'Content-Type: application/zip' );
		header( 'Content-Disposition: attachment; filename=' . $this->zipName );
		header( 'Content-Length: ' . filesize( $this->zipPath ) );
		header( 'Location: ' . $this->zipPath );
	}

}