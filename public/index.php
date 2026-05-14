<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// If the process environment defines APP_KEY as an empty string, immutable Dotenv will
// not replace it from .env, which triggers MissingAppKeyException. Clear only that case.
$appKeyEmptyInServer = array_key_exists('APP_KEY', $_SERVER) && $_SERVER['APP_KEY'] === '';
$appKeyEmptyInEnv = array_key_exists('APP_KEY', $_ENV) && $_ENV['APP_KEY'] === '';
$appKeyEmptyInGetenv = getenv('APP_KEY') !== false && getenv('APP_KEY') === '';
if ($appKeyEmptyInServer || $appKeyEmptyInEnv || $appKeyEmptyInGetenv) {
    unset($_SERVER['APP_KEY'], $_ENV['APP_KEY']);
    putenv('APP_KEY');
}

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
