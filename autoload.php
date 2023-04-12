<?php

spl_autoload_register(function ($class) {
  // convert namespace separators to directory separators
  $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
  $class = str_replace('MintBerry', 'src', $class);
  // echo $class . PHP_EOL;

  // load the class file
  require_once __DIR__ . '/' . $class . '.php';
});
