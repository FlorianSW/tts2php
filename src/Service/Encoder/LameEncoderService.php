<?php

namespace TTS2PHP\Service\Encoder;

use Lame\Lame;
use Lame\Settings\Encoding\Preset;
use Lame\Settings\Settings;
use TTS2PHP\Configuration\Config;

/**
 * Class LameEncoderService
 * @package TTS2PHP\Service\Encoder
 */
class LameEncoderService implements EncoderService {
	const FILE_EXTENSION = '.mp3';

	/** @var Config */
	private $config;

	private $outputFilename;

	public function execute( array $params ) {
		if ( !isset( $params['inputFilename'] ) ) {
			throw new EncoderException( 'No input file given.' );
		}
		if ( !isset( $params['outputFilename'] ) ) {
			throw new EncoderException( 'No output file given.' );
		}
		$this->outputFilename = $params['outputFilename'];
		// encoding type
		$encoding = new Preset();
		$encoding->setType( Preset::TYPE_STANDARD );

		$settings = new Settings( $encoding );
		$settings->setOption( '-b', '128' );
		$settings->setOption( '-m', 's' );

		$lame = new Lame( $this->config->get( 'encoderService' )['serviceBinary'], $settings );

		try {
			$lame->encode( $params['inputFilename'], $this->getResultFilename() );
		} catch(\RuntimeException $e) {
			var_dump($e->getMessage());
		}
	}

	public function getResultFilename() {
		return $this->outputFilename . self::FILE_EXTENSION;
	}

	public function setConfiguration( Config $config ) {
		$this->config = $config;
	}
}