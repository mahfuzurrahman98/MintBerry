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


// function extend($fileName) {
//   $view = str_replace('.', '/', $fileName);
//   $file = BASE_PATH . '/src/App/views/' . $view . '.php';

//   if (file_exists($file)) {
//     require_once $file;
//   } else {
//     die('Layout does not exist');
//   }
// }


function extend($content) {
  include BASE_PATH . '/src/App/views/layouts/master.php';
  // $view = str_replace('.', '/', $fileName);
  // $file = BASE_PATH . '/src/App/views/' . $view . '.php';

  // if (file_exists($file)) {
  //   // ob_start();
  //   include $file;
  //   // $content = ob_get_clean();
  //   // require_once $file;
  // } else {
  //   die('Layout does not exist');
  // }
}

function asset($file) {
  return BASE_PATH . '/public/' . $file;
}
