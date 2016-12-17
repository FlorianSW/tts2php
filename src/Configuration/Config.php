<?php

namespace TTS2PHP\Configuration;

use TTS2PHP\Logging\LoggerFactory;

/**
 * Class Config
 * @package TTS2PHP\Configuration
 */
class Config {

	/**
	 * @var array ALl possible configuration options.
	 */
	private $configuration = [
		'defaultLanguage' => 'de-DE',
	    'needsEncoding' => true,
	    'ttsService' => [],
	    'fileCacheDir' => __DIR__,
	    'encoderService' => [],
	    'objectCache' => [],
	];

	/**
	 * Config constructor.
	 *
	 * @param $configFile
	 * @throws ConfigurationException if the config file does not exist, isn't set (null) or does
	 * not return an array.
	 */
	public function __construct( $configFile ) {
		if ( $configFile ===  null ) {
			throw new ConfigurationException( 'The config file can not be null.' );
		}
		if ( !file_exists( $configFile ) ) {
			throw new ConfigurationException(
				'Could not locate configuration file: ' . $configFile );
		}

		$configuration = include $configFile;

		if ( !is_array( $configuration ) ) {
			throw new ConfigurationException(
				'The passed configuration file need to return an array.' );
		}

		$this->loadFromArray( $configuration );
	}

	/**
	 * Loads the configuration for this instance from the given array.
	 *
	 * @param array $config
	 */
	private function loadFromArray( array $config ) {
		$logger = LoggerFactory::getLogger( 'configuration' );
		foreach ( $config as $key => $value ) {
			if ( !array_key_exists( $key, $this->configuration ) ) {
				$logger->debug( 'The config option ' . $key . ' is unknown, ignoring it.' );
				continue;
			}

			$this->configuration[$key] = $value;
		}
	}

	/**
	 * Returns the value of the configuration with the given name.
	 *
	 * @param $name
	 * @return mixed
	 * @throws ConfigurationException
	 */
	public function get( $name ) {
		if ( !isset( $this->configuration[$name] ) ) {
			throw new ConfigurationException( 'The configuration with the name ' . $name .
				' does not exist.' );
		}
		return $this->configuration[$name];
	}
}