<?php

namespace MintBerry\App\Controllers;

// require __DIR__ . '/../../Core/JSONResponse.php';

use MintBerry\Core\Request;
use MintBerry\Core\JSONResponse;

class ProductController {
  use JSONResponse;

  private $request;

  public function __construct() {
    $this->request = new Request();
  }

  public function index() {
    $this->send(200, 'ProductController@index', $this->request->uri());
  }

  public function show() {
    echo 'ProductController@show';
  }

  public function store() {
    dd($this->request);
    echo 'ProductController@store';
  }
}
