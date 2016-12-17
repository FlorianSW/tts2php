<?php

namespace TTS2PHP\Pico2Wave;

/**
 * A wrapper class for the pico2wave binary, which should be installed on the system.
 * @package TTS2PHP\Pico2Wave
 */
class Pico2Wave {
	const FILE_EXTENSION = '.wav';

	private $picoBinary = '/usr/bin/pico2wave';

	private $text;

	private $language;

	private $filename = 'tts';

	private $output;

	public function __construct( $binaryPath ) {
		$this->picoBinary = $binaryPath;
	}

	public function setText( $text ) {
		$this->text = $text;
	}

	public function setLanguage( $lang ) {
		$this->language = $lang;
	}

	public function setFilename( $filename ) {
		$this->filename = $filename;
	}

	public function getFullFilename() {
		return $this->filename . self::FILE_EXTENSION;
	}

	public function run() {
		if ( $this->text === null || $this->text === '' ) {
			return 0;
		}
		$command = $this->picoBinary;
		if ( $this->language !== null ) {
			$command .= ' -l=' . escapeshellarg( $this->language );
		}
		if ( $this->filename !== null ) {
			$command .= ' -w=' . escapeshellarg( $this->getFullFilename() );
		}

		$command .= ' ' . escapeshellarg( $this->text );

		exec( $command , $this->output, $exitCode );

		return $exitCode;
	}
}