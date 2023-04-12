<?php

namespace Scandiweb\Core;

class Request {
  public $queryParams = [];
  public $body = [];

  public  function __construct() {
    $this->queryParams = $_GET;
    $this->body = $_POST;
  }

  public function uri() {
    return trim(
      parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
      '/'
    );
  }

  public function method() {
    return $_SERVER['REQUEST_METHOD'];
  }
}
