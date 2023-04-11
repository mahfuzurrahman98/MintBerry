<?php
// echo __DIR__;
// die;

require __DIR__ . '/autoload.php';

use Scandiweb\Core\Router;

$router = new Router();

$router->get('/products/index', 'ProductController', 'index');

$router->route();
