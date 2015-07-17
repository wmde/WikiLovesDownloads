<?php

class ArchiveCreator {

	private $zipName;
	private $zipPath;

	/** @var ZipArchive */
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
		if ( $currentTime > $fileTime && $file !== '.' && $file !== '..' ) {
				unlink( '../writable/' . $file );
		}
	}

	public function zipCreate() {
		$this->setName();
		$this->zip = new ZipArchive;
		$this->zip->open( $this->zipPath, ZipArchive::CREATE );
	}

	private function setName() {
		$stamp = uniqid();
		$this->zipName = 'WLD-' . $stamp . '.zip';
		$this->zipPath = '../writable/' . $this->zipName;
	}

	public function addFiles( $fileName, $content ) {
		$this->zip->addFromString( $fileName, $content );
	}

	public function download() {
		$this->zip->close();
		header( 'Content-Type: application/zip' );
		header( 'Content-Disposition: attachment; filename=' . $this->zipName );
		header( 'Location: ' . $this->zipPath );
	}

}