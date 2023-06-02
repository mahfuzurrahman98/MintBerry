<?php

namespace MintBerry\Core;


class Controller {
  protected function model($model) {
    echo 'Model: ' . $model;
    require_once '../app/models/' . $model . '.php';
    return new $model();
  }

  protected function render($view, $data = []) {
    $view = str_replace('.', '/', $view);
    $file = BASE_PATH . '/src/App/views/' . $view . '.php';
    if (file_exists($file)) {
      extract($data);
      require_once $file;
    } else {
      die('View does not exist');
    }
  }
}
