<?php

namespace MintBerry\Core;

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
        'message' => $e->getMessage(),
        'data' => $host
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

  public function bindParams($statement, $params) {
    foreach ($params as $key => $value) {
      // echo $key . ' => ' . $value . ' | ';
      $statement->bindParam($key, $value);
    }
    // die;
    return $statement;
  }

  public function execute($query, $params = []) {
    $statement = $this->pdo->prepare($query);
    if (!empty($params)) {
      $statement = $this->bindParams($statement, $params);
    }
    // dd($statement);

    // dd($statement->debugDumpParams());
    $statement->execute();
    return $statement;
  }

  public function getLastInsertId() {
    return $this->pdo->lastInsertId();
  }

  public function rowCount($query, $params = []) {
    $statement = $this->execute($query, $params);
    return $statement->rowCount();
  }

  public function fetch($query, $params = []) {
    $statement = $this->execute($query, $params);
    return $statement->fetch(PDO::FETCH_OBJ);
  }

  public function fetchAll($query, $params = []) {
    $statement = $this->execute($query, $params);
    return $statement->fetchAll(PDO::FETCH_OBJ);
  }
}
