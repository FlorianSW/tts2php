<?php

namespace TTS2PHP\Service\TTS;

use TTS2PHP\Configuration\Config;
use TTS2PHP\Pico2Wave\Pico2Wave;

/**
 * Class Pico2WaveService
 * @package TTS2PHP\Service\TTS
 */
class Pico2WaveService implements TTSService {
	/** @var Config */
	private $config;

	/** @var Pico2Wave */
	private $pico;

	private $supportedLangCodes = [
		'de-DE', 'en-US', 'en-GB', 'es-ES', 'fr-FR', 'it-IT',
	];

	/**
	 * @param Config $config
	 */
	public function setConfiguration( Config $config ) {
		$this->config = $config;
	}

	/**
	 * @return Pico2Wave
	 */
	private function getPico() {
		if ( $this->pico === null ) {
			$this->pico = new Pico2Wave( $this->config->get( 'ttsService' )['serviceBinary'] );
		}

		return $this->pico;
	}

	public function execute( array $params ) {
		$pico = $this->getPico();
		if ( isset( $params['text'] ) ) {
			$pico->setText( $params['text'] );
		}
		if ( isset( $params['lang'] ) ) {
			$pico->setLanguage( $params['lang'] );
		}
		if ( isset( $params['filename'] ) ) {
			$pico->setFilename( $params['filename'] );
		}

		return $pico->run();
	}

	public function isValidLangCode( $code ) {
		return in_array( $code, $this->supportedLangCodes );
	}

	public function getResultFilename() {
		return $this->getPico()->getFullFilename();
	}
}