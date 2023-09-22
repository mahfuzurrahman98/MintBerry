<?php

use MintBerry\Core\Router;
use MintBerry\App\Controllers\ProductController;

$router = new Router();


$router->prefix('/products', function ($router) {
  $router->disableCsrfProtection();
  $router->get('/', ProductController::class, 'index');
  $router->get('/show', ProductController::class, 'show');
  $router->post('/', ProductController::class, 'store')->middleware('TestMiddleware');
  $router->put('/', ProductController::class, 'update');
  $router->delete('/', ProductController::class, 'delete');
});
