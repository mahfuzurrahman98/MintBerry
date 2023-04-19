<?php


const BASE_PATH = __DIR__;

require BASE_PATH . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

require BASE_PATH . '/src/Core/functions.php';

ini_set('display_errors', env('APP_DEBUG'));

require BASE_PATH . '/src/App/helpers.php';
require BASE_PATH . '/src/App/routes.php';
require BASE_PATH . '/src/Core/autoload.php';
