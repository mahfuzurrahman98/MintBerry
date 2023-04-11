<?php

class Model {
  protected $db;

  public function __construct() {
    // Initialize the database connection
    $this->db = new PDO('mysql:host=localhost;dbname=my_database', 'username', 'password');
  }

  protected function query($sql, $params = []) {
    // Prepare and execute the SQL query
    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);

    // Return the result set
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
