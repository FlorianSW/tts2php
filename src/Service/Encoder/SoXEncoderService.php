<?php

namespace TTS2PHP\Service\Encoder;

use Lame\Lame;
use Lame\Settings\Encoding\Preset;
use Lame\Settings\Settings;
use TTS2PHP\Configuration\Config;
use TTS2PHP\Logging\LoggerFactory;

/**
 * Class SoXEncoderService
 * @package TTS2PHP\Service\Encoder
 */
class SoXEncoderService implements EncoderService {
	/** @const string */
	const FILE_EXTENSION = '.mp3';

	/** @var Config */
	private $config;

	/** @var string */
	private $outputFilename;

	public function execute( array $params ) {
		$logger = LoggerFactory::getLogger( 'sox' );
		if ( !isset( $params['inputFilename'] ) ) {
			throw new EncoderException( 'No input file given.' );
		}
		if ( !isset( $params['outputFilename'] ) ) {
			throw new EncoderException( 'No output file given.' );
		}
		$logger->debug( 'Setting output file: ' . $params['outputFilename'] );
		$this->outputFilename = $params['outputFilename'];
		$config = $this->config->get( 'encoderService' );
		$soxCommand = $config['serviceBinary'];
		$soxCommand .= ' ' . $params['inputFilename'];
		if ( isset( $config['channels'] ) ) {
			$logger->debug( 'Requested channels: ' . $config['channels'] );
			$soxCommand .= ' -c ' . $config['channels'];
		}
		if ( isset( $config['rate'] ) ) {
			$logger->debug( 'Requested rate: ' . $config['rate'] );
			$soxCommand .= ' -r ' . $config['rate'];
		}
		$soxCommand .= ' ' . $this->getResultFilename();

		$logger->debug( 'SoX command: ' . $soxCommand );
		exec( $soxCommand, $output, $exitCode );

		if ( $exitCode !== 0 ) {
			$logger->error( 'SoX command failed: ' . $soxCommand );
			throw new EncoderException( 'Encoding failed using sox.' );
		}
	}

	public function getResultFilename() {
		return $this->outputFilename . self::FILE_EXTENSION;
	}

	public function setConfiguration( Config $config ) {
		$this->config = $config;
	}
}