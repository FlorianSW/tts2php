<?php

namespace TTS2PHP\Logging;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

/**
 * Class LoggerFactory
 * @package TTS2PHP\Logging
 */
class LoggerFactory {
	private static $instances = [];

	/**
	 * @param string $channel The channel for this logger
	 * @return LoggerInterface
	 */
	public static function getLogger( $channel ) {
		if ( !isset( self::$instances[$channel] ) ) {
			$log = new Logger( 'name' );
			$log->pushHandler( new StreamHandler( sprintf( LOG_FILE, $channel ), DEFAULT_LOG_LEVEL ) );
			self::registerLogger( $channel, $log );
		}

		return self::$instances[$channel];
	}

	/**
	 * Registers a logger to a specific channel.
	 *
	 * @param $channel
	 * @param LoggerInterface $logger
	 * @throws \LogicException If the channel already has a logger assigned.
	 */
	public static function registerLogger( $channel, LoggerInterface $logger ) {
		if ( isset( self::$instances[$channel] ) && self::$instances[$channel] !== $logger ) {
			throw new \LogicException(
				'The logging channel ' . $channel .
				' has already a logger assigned. Can not assign another during runtime.' );
		}
		self::$instances[$channel] = $logger;
	}
}