<?php

namespace Scandiweb\Core;

use PDO;
use PDOException;

class Database {
  private static $instance = null;
  private $pdo;

  private function __construct() {
    $host = env('DB_HOST');
    $port = env('DB_PORT', null);
    $dbname = env('DB_DATABASE');
    $user = env('DB_USERNAME');
    $pass = env('DB_PASSWORD');

    $port = $port ? ':' . $port : '';
    $dsn = 'mysql:host=' . $host . $port . ';dbname=' . $dbname;

    $options = array(
      PDO::ATTR_PERSISTENT => true,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    );

    try {
      $this->pdo = new PDO($dsn, $user, $pass, $options);
    } catch (PDOException $e) {
      http_response_code(500);
      echo json_encode([
        'success' => false,
        'status' => 500,
        'message' => $e->getMessage()
      ]);
      die;
    }
  }

  public static function getInstance() {
    if (self::$instance === null) {
      self::$instance = new Database();
    }
    return self::$instance;
  }

  public function bindValues($statement, $values) {
    foreach ($values as $key => $value) {
      $statement->bindValue($key, $value);
    }
    return $statement;
  }

  public function execute($query, $values = []) {
    $statement = $this->pdo->prepare($query);
    if (!empty($values)) {
      $statement = $this->bindValues($statement, $values);
    }

    $statement->execute();
    return $statement;
  }

  public function getLastInsertId() {
    return $this->pdo->lastInsertId();
  }

  public function rowCount($query, $values = []) {
    $statement = $this->execute($query, $values);
    return $statement->rowCount();
  }

  public function fetch($query, $values = []) {
    $statement = $this->execute($query, $values);
    return $statement->fetch(PDO::FETCH_OBJ);
  }

  public function fetchAll($query, $values = []) {
    $statement = $this->execute($query, $values);
    return $statement->fetchAll(PDO::FETCH_OBJ);
  }

  // three more helper classes
  public function beginTransaction() {
    $this->pdo->beginTransaction();
  }

  public function commit() {
    $this->pdo->commit();
  }

  public function rollBack() {
    $this->pdo->rollBack();
  }
}
