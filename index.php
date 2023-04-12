<?php


require __DIR__ . '/autoload.php';
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/src/Core/functions.php';
require __DIR__ . '/src/App/helpers.php';
require __DIR__ . '/routes.php';
require __DIR__ . '/config.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
