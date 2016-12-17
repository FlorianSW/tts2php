<?php

namespace TTS2PHP\Cache;

/**
 * An interface for a cache backend, which can be configured as a backend for the object cache.
 * @package TTS2PHP\Cache
 */
interface CacheBackend {
	function set( $key, $value, $expiration = 0 );

	function get( $key );

	function has( $key );
}