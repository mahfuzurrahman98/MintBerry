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

  public static function attempt($email, $password) {
    $db = Database::getInstance();
    $stmt = $db->execute("SELECT * FROM users WHERE email=:email AND deleted_at IS NULL", ['email' => $email]);
    $data = $stmt->fetch(\PDO::FETCH_OBJ);

    if (Hash::verify($password, $data->password)) {
      $user = [];

      $user['id'] = $data->id;
      if (isset($data->name)) {
        $user['name'] = $data->name;
      }
      if (isset($data->username)) {
        $user['username'] = $data->username;
      }
      if (isset($data->email)) {
        $user['email'] = $data->email;
      }
      $_SESSION['user'] = (object)$user;
      return true;
    }
    return false;
  }
}
