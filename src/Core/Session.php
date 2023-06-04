<?php

namespace MintBerry\Core;

class Session {
  private static $sessionStarted = false;

  public static function start() {
    if (self::$sessionStarted == false) {
      session_start();
      self::$sessionStarted = true;
    }
  }

  public static function put($key, $value) {
    self::start();
    $_SESSION[$key] = $value;
  }

  public static function has($key) {
    self::start();
    return isset($_SESSION[$key]);
  }

  public static function get($key) {
    self::start();
    return $_SESSION[$key] ?? null;
  }

  public static function all() {
    self::start();
    return $_SESSION;
  }

  public static function forget($key) {
    self::start();
    unset($_SESSION[$key]);
  }

  public static function destroy() {
    self::start();
    session_destroy();
    self::$sessionStarted = false;
  }
}
