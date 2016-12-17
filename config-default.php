<?php

/**
 * The default log level will be used for each new logger.
 */
define( 'DEFAULT_LOG_LEVEL', \Monolog\Logger::WARNING );
/**
 * The log file is the file to which the log output should be streamed, to. The first %s will be
 * replaced by the channel of the logger.
 */
define( 'LOG_FILE', 'log_%s.log' );

return [
	'defaultLanguage' => 'de-DE',
	'needsEncoding' => true,
	'fileCacheDir' => dirname( __DIR__ ) . '/cache/',
	'ttsService' => [
		'class' => 'TTS2PHP\\Service\\TTS\\Pico2WaveService',
		'serviceBinary' => '/usr/bin/pico2wave',
	],
	'encoderService' => [
		'class' => 'TTS2PHP\\Service\\Encoder\\SoXEncoderService',
		'serviceBinary' => '/usr/bin/sox',
		'channels' => '2',
		'rate' => '44100',
	],
	'objectCache' => [
		'class' => 'TTS2PHP\\Cache\\RedisCache',
		'arguments' => [],
	],
];