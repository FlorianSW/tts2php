# tts2php
This WebTTS service is mostly based on the idea of https://github.com/openhab/openhab/wiki/Use-local-TTS-with-squeezebox and is a
bit extended. It now also uses caching, so that not each request has to be transformed using the configured TTS binary again. It
also now supports different TTS and encoding binaries (default is pico2wave and sox).

# Requirements
* A TTS binary on your machine, where the script should run, e.g. pico2wave (pre-configured, you just need to install the binary, e.g.
  `apt-get install libttspico-utils`)
* A webserver (e.g. nginx)
* php (>= 5.4)
* composer
* (optional) An object cache, like redis (redis is the only one implemented in this repository for now)

# Installation
Simply clone or download and unpack this repository to your webserver and run `composer update` to fetch the latest versions of all
needed dependencies. That's basically it. However, you need to install at least one TTS Service and, if it doesn't output mp3 files
directly, an encoder (such as sox) to generate an mp3 file from the resulting file of your TTS Service. E.g. the following steps are needed
to setup this script with pico2wave and sox:

* Install pico2wave: `apt-get install libttspico-utils`
* Install sox and the mp3 library: `apt-get install libsox-fmt-mp3`
* Install redis-server for caching: `apt-get install redis-server`
* Clone this repository
* Copy the configuration file `config-default.php` to `config.php`
* Review the settings in `config.php`, especially:
 * `defaultLanguage`: If no `lang` parameter is passed to the URL of this script, this is the language code, which will be used by
    default to transform the text into audio. The value depends on the supported language codes of the TTS binary.
 * `ttsService` -> `serviceBinary`: The location of the binary will be called directly by this script, so it must exist.
 * `encoderService`: Review the parameters passed to sox to make sure they make sense for your use case. See the manpage of sox for other
   allowed parameters.
 * `objectCache` -> `arguments`: The named arguments here will be passed directly to the `predis` redis client, see https://packagist.org/packages/predis/predis
   to learn the allowed values. Especially authentication data must be provided, if your redis-server needs authentication.

Request the script to convert text to audio: http://<your-host>:<your-port>/path/to/index.php?msg=Hello%20World!
