<?php

use MintBerry\Core\Router;

$router = new Router();

// $router->get('/products/index', 'ProductController@index');
// ->middleware('TestMiddleware');
// $router->get('/products/show', 'ProductController@show');
// $router->post('/products/store', 'ProductController@store');
// $router->put('/products/update', 'ProductController@update');
// $router->delete('/products/delete', 'ProductController@delete');

// $router->get('/test', 'ProductController@test');
// $router->post('/post-req', 'ProductController@postRreq');

$router->prefix('/products', function ($router) {
  $router->get('/index', 'ProductController@index')->middleware('TestMiddleware');
  $router->get('/show', 'ProductController@show');
  $router->post('/store', 'ProductController@store');
  $router->put('/update', 'ProductController@update');
  $router->delete('/delete', 'ProductController@delete');
});
