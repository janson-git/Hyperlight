<?php

require_once __DIR__ . '/lib/SplClassLoader.php';

$autoloader = new SplClassLoader('HyperLight', __DIR__ . '/lib');
$autoloader->register();
