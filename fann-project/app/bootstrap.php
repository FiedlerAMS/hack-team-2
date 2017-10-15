<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/exc_handler.php';
require_once __DIR__ . '/fallbacks.php';

$development = true;
$loader = new Nette\DI\ContainerLoader(__DIR__ . '/../temp', $development);

$containerClass = $loader->load(function($compiler) {
    $compiler->loadConfig(__DIR__ . '/config.neon');
});

return new $containerClass;