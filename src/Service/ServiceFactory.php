<?php

namespace TTS2PHP\Service;

use TTS2PHP\Configuration\Config;
use TTS2PHP\Service\Encoder\EncoderService;
use TTS2PHP\Service\TTS\TTSService;

interface ServiceFactory {
	/**
	 * @param Config $config
	 * @return TTSService|EncoderService
	 */
	static function build( Config $config );
}