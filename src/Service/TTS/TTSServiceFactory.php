<?php

namespace TTS2PHP\Service\TTS;

use TTS2PHP\Configuration\Config;
use TTS2PHP\Service\AbstractServiceFactory;

/**
 * Class TTSServiceFactory
 * @package TTS2PHP\Service\TTS
 */
class TTSServiceFactory extends AbstractServiceFactory {
	/**
	 * @param Config $config
	 * @return TTSService
	 * @throws \Exception
	 */
	public static function build( Config $config ) {
		return parent::build( $config );
	}

	protected function getClass( Config $config ) {
		return $config->get( 'ttsService' )['class'];
	}

	protected function isValid( $service ) {
		return $service instanceof TTSService;
	}
}