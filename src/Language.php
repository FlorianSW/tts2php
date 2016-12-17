<?php

namespace TTS2PHP;

use TTS2PHP\Service\TTS\TTSServiceFactory;

/**
 * Class Language
 * @package TTS2PHP
 */
class Language {
	/** @var string a valid language code, which is represented by this Language object. */
	private $languagCode;

	/** @var Configuration\Config */
	private $config;

	/**
	 * Language constructor. Takes the lang option passed to the request or, as a default, the
	 * configured default language to construct the Language object.
	 * @param Application $app
	 */
	public function __construct( Application $app ) {
		$this->config = $app->getConfig();
		if ( $app->getRequest()->has( 'lang' ) ) {
			$this->setLanguageCode( $app->getRequest()->getVal( 'lang' ) );
		} else {
			$this->setLanguageCode( $app->getConfig()->get( 'defaultLanguage' ) );
		}
	}

	/**
	 * Validates, if the language code can be handled by the configured TTSService, if not, will
	 * throw a LanguageException.
	 *
	 * @param $code
	 * @throws LanguageException
	 */
	private function setLanguageCode( $code ) {
		$ttsService = TTSServiceFactory::build( $this->config );
		if ( !$ttsService->isValidLangCode( $code ) ) {
			throw new LanguageException(
				'The language code ' . $code .
				' can not be handled by the currently configured TTS service.' );
		}

		$this->languagCode = $code;
	}

	/**
	 * Returns the language code, which guaranteed to be valid for the configured TTSService.
	 *
	 * @return string
	 */
	public function getCode() {
		return $this->languagCode;
	}
}