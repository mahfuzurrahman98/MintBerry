<?php

use Scandiweb\Core\Router;

$router = new Router();

$router->get('/products/index', 'ProductController', 'index');
$router->post('/products/store', 'ProductController', 'store');

$router->route();
