<?php

namespace TTS2PHP\Cache;

use Predis\Client;

/**
 * Wrapper around the Predis library.
 * @package TTS2PHP\Cache
 */
class RedisCache implements CacheBackend {
	private $client;

	public function __construct( array $params = [] ) {
		$this->client = new Client( $params );
	}

	public function set( $key, $value, $expiration = null ) {
		if ( $expiration !== null ) {
			$this->client->set( $key, $value, 'ex', $expiration );
		} else {
			$this->client->set( $key, $value );
		}
	}

	public function get( $key ) {
		return $this->client->get( $key );
	}

	public function has( $key ) {
		return $this->client->get( $key ) !== null;
	}
}