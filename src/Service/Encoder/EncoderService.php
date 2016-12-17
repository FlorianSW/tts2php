<?php

namespace TTS2PHP\Service\Encoder;

use TTS2PHP\Service\Service;

/**
 * An interface for encoder services. The result of this service MUST be an mp3 file.
 * @package TTS2PHP\Service\Encoder
 */
interface EncoderService extends Service {
	function getResultFilename();
}