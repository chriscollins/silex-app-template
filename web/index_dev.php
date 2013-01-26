<?php

use Symfony\Component\ClassLoader\DebugClassLoader;
use Symfony\Component\HttpKernel\Debug\ErrorHandler;
use Symfony\Component\HttpKernel\Debug\ExceptionHandler;

require_once __DIR__ . '/../vendor/autoload.php';

ini_set('display_errors', 1);
error_reporting(-1);
DebugClassLoader::enable();
ErrorHandler::register();
if (php_sapi_name() !== 'cli') {
    ExceptionHandler::register();
}

$app = new Silex\Application();
require __DIR__ . '/../resources/config/dev.php';
require __DIR__ . '/../src/app.php';
require __DIR__ . '/../src/controllers.php';
$app->run();
