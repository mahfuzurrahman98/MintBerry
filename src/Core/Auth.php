<?php

namespace MintBerry\Core;

use MintBerry\Core\Database;
use MintBerry\Core\Hash;

class Auth {
  protected $db;
  public function __construct() {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
    $this->db = Database::getInstance();
  }

  public static function login($email, $password) {
    $db = Database::getInstance();
    $stmt = $db->execute("SELECT * FROM users WHERE email=:email AND deleted_at IS NULL", ['email' => $email]);
    $data = $stmt->fetch(\PDO::FETCH_OBJ);
    if (Hash::verify($password, $data->password)) {
      $_SESSION['user_id'] = $data->id;
      return true;
    }
    return false;
  }
}
