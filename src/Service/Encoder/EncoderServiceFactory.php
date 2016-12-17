<?php

namespace TTS2PHP\Service\Encoder;

use TTS2PHP\Configuration\Config;
use TTS2PHP\Service\AbstractServiceFactory;

/**
 * Class EncoderServiceFactory
 * @package TTS2PHP\Service\Encoder
 */
class EncoderServiceFactory extends AbstractServiceFactory {
	/**
	 * @param Config $config
	 * @return EncoderService
	 * @throws \Exception
	 */
	public static function build( Config $config ) {
		return parent::build( $config );
	}

	/**
	 * @param Config $config
	 * @return string
	 */
	protected function getClass( Config $config ) {
		return $config->get( 'encoderService' )['class'];
	}

	/**
	 * @param $service
	 * @return bool
	 */
	protected function isValid( $service ) {
		return $service instanceof EncoderService;
	}
}