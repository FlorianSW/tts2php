<?php

namespace TTS2PHP;

use TTS2PHP\Configuration\Config;
use TTS2PHP\Service\TTS\TTSServiceFactory;

/**
 * Class Application
 * @package TTS2PHP
 */
class Application {
	/** @var Request */
	private $request;

	/** @var Config */
	private $configuration;

	/** @var Language */
	private $language;

	/**
	 * Main entry point. It will setup the application to create a response to the request and
	 * will trigger the output of the tts file.
	 *
	 * @param $config
	 */
	public function run( $config ) {
		$this->request = new Request();
		$this->configuration = new Config( $config );
		$this->language = new Language( $this );
		$output = new Output( $this );

		$ttsService = TTSServiceFactory::build( $this->getConfig() );
		$output->outputFromTTSService( $ttsService );
	}

	/**
	 * @return Request
	 */
	public function getRequest() {
		return $this->request;
	}

	/**
	 * @return Config
	 */
	public function getConfig() {
		return $this->configuration;
	}

	/**
	 * @return Language
	 */
	public function getLanguage() {
		return $this->language;
	}
}