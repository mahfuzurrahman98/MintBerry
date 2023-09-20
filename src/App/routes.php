<?php

use MintBerry\Core\Router;
use MintBerry\App\Controllers\ProductController;

$router = new Router();


$router->prefix('/products', function ($router) {
  $router->disableCsrfProtection();
  $router->get('/index', ProductController::class, 'index');
  $router->get('/show', ProductController::class, 'show');
  $router->post('/store', ProductController::class, 'store');
  $router->put('/update', ProductController::class, 'update');
  $router->delete('/delete', ProductController::class, 'delete');
});
