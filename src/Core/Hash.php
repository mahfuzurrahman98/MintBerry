<?php

namespace MintBerry\Core;

class Hash {
  public static function make($plainPassword) {
    return password_hash($plainPassword, PASSWORD_DEFAULT);
  }

  public static function verify($plainPassword, $hashedPassword) {
    return password_verify($plainPassword, $hashedPassword);
  }
}
