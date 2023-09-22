<?php

namespace MintBerry\App\Middlewares;

use MintBerry\Core\Request;
use MintBerry\Core\JSONResponse;

class TestMiddleware {
  use JSONResponse;

  public function handle() {
    $request = new Request();

    if (!$request->hasHeader('age') || $request->getHeader('age') < 18) {
      $this->respondJSON(403, 'You are not allowed to access this resource');
    }
  }
}
