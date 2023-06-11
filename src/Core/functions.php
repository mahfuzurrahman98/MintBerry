<?php

use MintBerry\Core\Session;

function dd($data) {
  echo '<pre>';
  var_dump($data);
  echo '</pre>';
  die();
}

function env($key, $default = null) {
  return !$_ENV[$key] ? $default : $_ENV[$key];
}

function asset($folder) {
  return "/$folder";
}

function csrf_token() {
  Session::start();
  $token = bin2hex(random_bytes(32));
  Session::put('csrf_token', $token);
  return $token;
}

function csrf_input() {
  echo '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

function redirect($url) {
  header('Location: ' . $url);
  exit;
}

function redirect_with_success($url, $success) {
  Session::start();
  Session::put('success', $success);
  redirect($url);
}

function redirect_with_error($url, $error) {
  Session::start();
  Session::put('error', $error);
  redirect($url);
}

function abort($code) {
  $errorMessages = require_once(BASE_PATH . '/src/Core/errors.php');
  require_once BASE_PATH . '/src/Core/error-html.php';
}

function includeWithData($file, $data = []) {
  extract($data);
  $view = str_replace('.', '/', $file);
  $file = BASE_PATH . '/src/App/views/' . $view . '.php';
  if (file_exists($file)) {
    extract($data);
    require_once $file;
  } else {
    die('View does not exist');
  }
}
