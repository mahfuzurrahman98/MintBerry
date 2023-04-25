<?php

namespace MintBerry\App\Middlewares;

use MintBerry\Core\Request;

class TestMiddleware {
  public function handle() {
    $request = new Request();

    if (!$request->hasHeader('age') || $request->getHeader('age') < 18) {
      die("not allowed");
    }
  }
}
