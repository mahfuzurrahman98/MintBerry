<?php

namespace MintBerry\Core;

class Session {
  public function __construct() {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
  }

  public static function put($key, $value) {
    $_SESSION[$key] = $value;
  }

  public static function has($key) {
    return isset($_SESSION[$key]);
  }

  public static function get($key) {
    return $_SESSION[$key];
  }

  public static function forget($key) {
    unset($_SESSION[$key]);
  }

  public static function destroy() {
    session_destroy();
  }
}
