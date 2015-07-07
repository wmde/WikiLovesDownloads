<?php

class ArchiveCreator {

	private $zipName;
	private $zipPath;
	private $tempPath;
	private $zip;

	public function cleanUp() {
		$writableDir = '../writable/';
		$zipDeletionList = scandir( $writableDir );

		foreach ( $zipDeletionList as $file ) {
			$currentTime = time();
			$fileTime = (int)filemtime( '../writable/' . $file ) + 3600;

			$this->checkRemove( $currentTime, $fileTime, $file );
		}
	}

	private function checkRemove( $currentTime, $fileTime, $file ) {
		if ( $currentTime > $fileTime ) {
			if ( $file !== '.' && $file !== '..' ) {
				unlink( '../writable/' . $file );
			}
		}
	}

	public function zipCreate() {
		$this->setName();

		mkdir( $this->tempPath );
		$this->zip = new ZipArchive;

		$zipCreate = fopen( $this->zipPath, 'w' );
		fclose( $zipCreate );
	}

	private function setName() {
		$stamp = uniqid();

		$this->tempPath = '../writable/temp' . $stamp;

		$this->zipName = 'WLD-' . $stamp . '.zip';
		$this->zipPath = '../writable/' . $this->zipName;
	}

	public function zipFiles( $fileName, $content ) {
		$filePath = $this->tempPath . $fileName;
		file_put_contents( $filePath, $content );
		$this->addFiles( $filePath, $fileName );
		unlink( $filePath );
	}

	private function addFiles( $filePath, $fileName ) {
		$this->zip->open( $this->zipPath );
		$this->zip->addFile( $filePath, $fileName );
		$this->zip->close();
	}

	public function download() {
		rmdir( $this->tempPath );
		header( 'Content-Type: application/zip' );
		header( 'Content-Disposition: attachment; filename=' . $this->zipName );
		header( 'Content-Length: ' . filesize( $this->zipPath ) );
		header( 'Location: ' . $this->zipPath );
	}

}