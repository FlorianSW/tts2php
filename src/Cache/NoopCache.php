<?php

namespace TTS2PHP\Cache;

/**
 * A no-op cache. It will do nothing.
 *
 * @package TTS2PHP\Cache
 */
class NoopCache implements CacheBackend {
	public function get( $key ) {
		return null;
	}

	public function has( $key ) {
		return false;
	}

	public function set( $key, $value, $expiration = 0 ) {}
}