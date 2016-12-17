<?php
/**
 * Entry point for TTS2PHP.
 */
require __DIR__ . '/vendor/autoload.php';

$ttsService = new \TTS2PHP\Application();
$ttsService->run( __DIR__ . '/config.php' );