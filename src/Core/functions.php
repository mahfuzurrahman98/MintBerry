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
