<?php

namespace MintBerry\Core;

use MintBerry\Core\Session;


class Controller {
  public function __construct() {
    Session::start();
  }
  protected function model($model) {
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

  public function __destruct() {
    if (Session::has('csrf_token')) {
      Session::forget('csrf_token');
    }
  }
}
