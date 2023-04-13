<?php

use MintBerry\Core\Router;

$router = new Router();

$router->get('/products/index', 'ProductController', 'index');
$router->get('/products/show', 'ProductController', 'show');
$router->post('/products/store', 'ProductController', 'store');
$router->put('/products/update', 'ProductController', 'update');
$router->delete('/products/delete', 'ProductController', 'delete');


$router->route();
