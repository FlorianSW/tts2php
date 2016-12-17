<?php

namespace TTS2PHP;

/**
 * Class Request
 * @package TTS2PHP
 */
class Request {
	private $data = [];

	public function __construct() {
		$this->data = $_POST + $_GET;
	}

	/**
	 * Returns the value of the request parameter with the given name, or the default value if
	 * not set.
	 *
	 * @param $key
	 * @param null $default
	 * @return null
	 */
	public function getVal( $key, $default = null ) {
		if ( !isset( $this->data[$key] ) ) {
			return $default;
		}

		return $this->data[$key];
	}

	/**
	 * Checks, if the given key is a parameter in this request.
	 *
	 * @param $key
	 * @return bool
	 */
	public function has( $key ) {
		return isset( $this->data[$key] );
	}
}