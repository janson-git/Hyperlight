<?php

require_once __DIR__ . '/lib/SplClassLoader.php';

define('HYPERLIGHT_ROOT_PATH', __DIR__ . '/HyperLight');
define('HYPERLIGHT_LANGUAGES_PATH', HYPERLIGHT_ROOT_PATH . '/languages');


$autoloader = new SplClassLoader('HyperLight', __DIR__);
$autoloader->register();
