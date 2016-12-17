<?php

namespace TTS2PHP\Service;

use TTS2PHP\Configuration\Config;

interface Service {
	function setConfiguration( Config $config );

	function execute( array $parameters );
}