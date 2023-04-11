<?php

namespace Scandiweb\App\Controllers;

// require __DIR__ . '/../../Core/JSONResponse.php';

use Scandiweb\Core\JSONResponse;

class ProductController {
  use JSONResponse;
  public function index() {
    $this->send(200, 'ProductController@index', array('test' => 'test'));
  }

  public function show() {
    echo 'ProductController@show';
  }
}
