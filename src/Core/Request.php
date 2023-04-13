<?php

namespace MintBerry\Core;

class Request {
  private $queryParams = [];
  private $body = [];
  private $uri = '';
  private $path = '';
  private $method = '';
  private $headers = [];
  private $cookies = [];
  private $files = [];

  public  function __construct() {
    $this->queryParams = (object)$_GET;

    // if the content type is application/json then decode the body otherwise use $_POST
    if (isset($_SERVER['CONTENT_TYPE']) && strtolower($_SERVER['CONTENT_TYPE']) === 'application/json') {
      $this->body = (object)json_decode(file_get_contents('php://input'), true);
    } else {
      $this->body = (object)$_POST;
    }

    $this->uri = trim(
      parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
      '/'
    );

    $this->path = trim(
      parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
      '/'
    );

    $this->method = $_SERVER['REQUEST_METHOD'];

    $this->headers = (object)getallheaders();

    $this->cookies = (object)$_COOKIE;

    $this->files = $_FILES;
  }


  // uri & path
  public function uri() {
    return $this->uri;
  }

  public function path() {
    return $this->path;
  }


  // method
  public function method() {
    return $this->method;
  }


  // query params
  public function getQueryParams() {
    return $this->queryParams;
  }

  public function hasQueryParam($key) {
    return isset($this->queryParams->$key);
  }

  public function getQueryParam($key) {
    return $this->queryParams->$key;
  }


  // body
  public function getBody() {
    return $this->body;
  }

  public function hasBodyParam($key) {
    return isset($this->body->$key);
  }

  public function getBodyParam($key) {
    return $this->body->$key;
  }

  // files
  public function getFiles() {
    return $this->files;
  }

  public function hasFile($key) {
    return isset($this->files[$key]);
  }

  public function getFile($key) {
    return $this->files[$key];
  }


  // headers
  public function getHeaders() {
    return $this->headers;
  }

  public function hasHeader($key) {
    return isset($this->headers->$key);
  }

  public function getHeader($key) {
    return $this->headers->$key;
  }


  // cookies
  public function getCookies() {
    return $this->cookies;
  }

  public function getCookie($key) {
    return $this->cookies->$key;
  }

  public function hasCookie($key) {
    return isset($this->cookies->$key);
  }
}
