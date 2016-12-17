<?php

namespace TTS2PHP\Service\TTS;

use TTS2PHP\Service\Service;

/**
 * An interface for a TTS service, which can be used to transform text into an audio file.
 * Depending on the output (and the configuration of TTS2PHP), the file will probably be encoded
 * using one of the possible encoder services.
 *
 * @package TTS2PHP\Service\TTS
 */
interface TTSService extends Service {
	function getResultFilename();

	/**
	 * Must return true, if the language code can be handled by the underlying service. Returning
	 * true here and failing to convert a text to an audio file because of a false language code
	 * is not allowed.
	 *
	 * @param $code
	 * @return boolean
	 */
	function isValidLangCode( $code );
}