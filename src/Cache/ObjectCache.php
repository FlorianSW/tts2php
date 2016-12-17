<?php

namespace TTS2PHP\Cache;

use TTS2PHP\Configuration\Config;

/**
 * Class ObjectCache
 * @package TTS2PHP\Cache
 */
class ObjectCache {

	private $config;

	/**
	 * @var CacheBackend
	 */
	private $backend;

	/**
	 * @param Config $config
	 * @return ObjectCache
	 */
	public static function makeInstance( Config $config ) {
		$cache = new self();
		$cache->config = $config;
		$backend = $config->get( 'objectCache' );
		if ( $backend ) {
			$cache->backend = new $backend['class']( $backend['arguments'] );
		} else {
			$cache->backend = new NoopCache();
		}

		return $cache;
	}

	/**
	 * Returns the key for the filename for the given text.
	 *
	 * @param $text
	 * @return string
	 */
	public static function getFileCacheKey( $text ) {
		return md5( $text );
	}

	/**
	 * Returns the filename (without any extension), where the output of the TTS for the given
	 * text should be saved.
	 *
	 * The given $text should be the string and the text of the TTS in the following format:
	 * lang:text
	 *
	 * @param $text
	 * @return string
	 */
	public function getFilesystemCacheFileFor( $text ) {
		return $this->getFileCachePath() .
			ObjectCache::getFileCacheKey(
				$text
			);
	}

	/**
	 * Returns the cache key, that should be used to set and retrieve data from the cache.
	 *
	 * @return string
	 */
	public function makeKey() {
		$args = func_get_args();
		$key = 'tts2php';
		foreach ( $args as $arg ) {
			$arg = str_replace( ':', '%3A', $arg );
			$key = $key . ':' . $arg;
		}
		return strtr( $key, ' ', '_' );
	}

	/**
	 * @param $key
	 * @param $value
	 */
	public function set( $key, $value ) {
		$this->backend->set( $key, $value );
	}

	/**
	 * @param $key
	 * @return boolean
	 */
	public function has( $key ) {
		return $this->backend->has( $key );
	}

	/**
	 * @param $key
	 * @return string
	 */
	public function get( $key ) {
		return $this->backend->get( $key );
	}

	/**
	 * Returns the path of the file cache location.
	 *
	 * @return string
	 * @throws CacheException
	 */
	private function getFileCachePath() {
		$cacheDir = $this->config->get( 'fileCacheDir' );
		if ( !is_dir( $cacheDir ) ) {
			throw new CacheException( 'The cache directory ' . $cacheDir . ' isn\'t a directory.' );
		}
		if ( !is_writable( $cacheDir ) ) {
			throw new CacheException( 'The cache directory ' . $cacheDir . ' isn\'t writeable.' );
		}
		if ( substr( $cacheDir, -1 ) !== '/' ) {
			$cacheDir .= '/';
		}
		return $cacheDir;
	}
}