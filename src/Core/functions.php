<?php

function dd($data) {
  echo '<pre>';
  var_dump($data);
  echo '</pre>';
  die();
}

function env($key, $default = null) {
  return !$_ENV[$key] ? $default : $_ENV[$key];
}

function asset($file) {
  return BASE_PATH . '/public/' . $file;
}

function csrf_token() {
  session_start();
  $token = bin2hex(random_bytes(32));
  $_SESSION['csrf_token'] = $token;
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
  session_start();
  $_SESSION['success'] = $success;
  redirect($url);
}

function redirect_with_error($url, $error) {
  session_start();
  $_SESSION['error'] = $error;
  redirect($url);
}

function abort($code) {
  $errorMessages = require_once(BASE_PATH . '/src/Core/errors.php');
  require_once BASE_PATH . '/src/Core/error-html.php';
}
