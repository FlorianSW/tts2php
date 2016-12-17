<?php

namespace TTS2PHP\Service;

use TTS2PHP\Configuration\Config;

/**
 * Class AbstractServiceFactory
 * @package TTS2PHP\Service
 */
abstract class AbstractServiceFactory implements ServiceFactory {
	public static function build( Config $config ) {
		$factory = new static();
		$service = $factory->getClass( $config );
		$service = new $service;

		if ( !$factory->isValid( $service ) ) {
			throw new \Exception(
				'The service is not valid.' );
		}

		/** @var Service $service */
		$service->setConfiguration( $config );

		return $service;
	}

	abstract protected function isValid( $service );

	abstract protected function getClass( Config $config );
}