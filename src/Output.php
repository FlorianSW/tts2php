<?php

namespace TTS2PHP;

use TTS2PHP\Cache\ObjectCache;
use TTS2PHP\Logging\LoggerFactory;
use TTS2PHP\Service\Encoder\EncoderServiceFactory;
use TTS2PHP\Service\TTS\TTSService;

/**
 * Class Output
 * @package TTS2PHP
 */
class Output {
	private $app;

	public function __construct( Application $app ) {
		$this->app = $app;
	}

	/**
	 * Creates the mp3 file, which is the TTS representation of the requested text and outputs it
	 * as a response. The mp3 will be created using the passed TTSService and, if configured so
	 * (see the needsEncoding conifguration option), encoded using the configured encoding service.
	 *
	 * The resulting filename of the TTSService, or the EncoderService, depending on which touched
	 * the result last, will be saved using the configured ObjectCache, if any. If the text, in
	 * the given language, was already transformed to a mp3 TTS file and if this file still
	 * exists, it will be served directly in the response, without interacting with the
	 * TTSService or the EncoderService.
	 *
	 * @param TTSService $service
	 */
	public function outputFromTTSService( TTSService $service ) {
		$logger = LoggerFactory::getLogger( 'output' );
		$cacheLogger = LoggerFactory::getLogger( 'cache' );
		$request = $this->app->getRequest();
		$cache = ObjectCache::makeInstance( $this->app->getConfig() );
		$lang = $this->app->getLanguage()->getCode();
		$outputFile = $cache->getFilesystemCacheFileFor( $lang . ':' . $request->getVal( 'msg' ) );

		$logger->debug( 'Used output file: ' . $outputFile );
		$params = [
			'text' => $request->getVal( 'msg' ),
			'filename' => $outputFile,
			'lang' => $lang,
		];

		$cacheKey = $cache->makeKey(
			ObjectCache::getFileCacheKey( $lang . ':' .
				$request->getVal( 'msg' ) ),
			$params['lang']
		);
		$cacheLogger->debug( 'Used cache key: ' . $cacheKey );

		if ( $cache->has( $cacheKey ) ) {
			$cacheLogger->debug( 'Hit Found cached version for ' . $outputFile );
			$filename = $cache->get( $cacheKey );
		} else {
			$cacheLogger->debug( 'Miss: File ' . $outputFile . ' not yet cached.' );
			$filename = $this->createFile( $service, $params, $cache );
		}
		if ( $filename === null ) {
			$logger->error( 'Cache file is empty or file creation failed, return 404.' );
			$this->return404();
			return;
		}

		$getID3 = new \getID3();
		$fileInfo = $getID3->analyze( $filename );
		header( 'Content-Transfer-Encoding: binary' );
		header( 'Content-Type: audio/mpeg' );
		$logger->debug( 'File size: ' . filesize( $filename ) );
		header( 'Content-length: ' . filesize( $filename ) );
		header( 'Content-Disposition: filename=' . $filename );
		header( 'Cache-Control: no-cache' );
		$logger->debug( 'Bitrate: ' . round( $fileInfo['audio']['bitrate'] / 1000 ) );
		header( 'icy-br: ' . round( $fileInfo['audio']['bitrate'] / 1000 ) );
		header( 'icy-name: TTS' );
		readfile( $filename );
	}

	/**
	 * Encodes the given file to the given output filename using the configured encoding service.
	 *
	 * @param $inputFile
	 * @param $outputFile
	 * @return string
	 */
	private function encodeFile( $inputFile, $outputFile ) {
		$encoder = EncoderServiceFactory::build( $this->app->getConfig() );
		$params = [
			'inputFilename' => $inputFile,
		    'outputFilename' => $outputFile,
		];
		$encoder->execute( $params );

		return $encoder->getResultFilename();
	}

	/**
	 * @param TTSService $service
	 * @param array $params
	 * @param ObjectCache $cache
	 * @return string|null
	 */
	private function createFile( TTSService $service, array $params, ObjectCache $cache ) {
		$outputFile = $params['filename'];
		$exitCode = $service->execute( $params );

		if ( $exitCode !== 0 ) {
			return null;
		}

		$filename = $service->getResultFilename();
		if ( !file_exists( $filename ) ) {
			return null;
		}

		if ( $this->app->getConfig()->get( 'needsEncoding' ) ) {
			$filename = $this->encodeFile( $filename, $outputFile );
		}

		$cache->set(
			$cache->makeKey(
				ObjectCache::getFileCacheKey( $params['lang'] . ':' .
					$params['text'] ),
				$params['lang']
			),
			$filename
		);

		return $filename;
	}

	/**
	 * Sets the 404 header.
	 */
	private function return404() {
		header( 'HTTP/1.0 404 Not Found' );
	}
}