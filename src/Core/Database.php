<?php

namespace MintBerry\Core;

use PDO;
use PDOException;

class Database {
  private $host = $config['database']['host'];
  private $user = $config['database']['user'];
  private $pass = $config['database']['pass'];
  private $dbname =  $config['database']['dbname'];

  private $dbh;
  private $stmt;
  private $error;

  public function __construct() {
    // Set DSN
    $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
    // Set options 
    $options = array(
      PDO::ATTR_PERSISTENT => true,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    );
    // Create a new PDO instance
    try {
      $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
    }
    // Catch any errors
    catch (PDOException $e) {
      $this->error = $e->getMessage();
    }
  }

  public function query($query) {
    $this->stmt = $this->dbh->prepare($query);
  }

  public function bind($param, $value, $type = null) {
    if (is_null($type)) {
      switch (true) {
        case is_int($value):
          $type = PDO::PARAM_INT;
          break;
        case is_bool($value):
          $type = PDO::PARAM_BOOL;
          break;
        case is_null($value):
          $type = PDO::PARAM_NULL;
          break;
        default:
          $type = PDO::PARAM_STR;
      }
    }

    $this->stmt->bindValue($param, $value, $type);
  }

  public function execute() {
    return $this->stmt->execute();
  }

  public function resultSet() {
    $this->execute();
    return $this->stmt->fetchAll(PDO::FETCH_OBJ);
  }

  public function single() {
    $this->execute();
    return $this->stmt->fetch(PDO::FETCH_OBJ);
  }

  public function rowCount() {
    return $this->stmt->rowCount();
  }
}
