<?php

namespace  MintBerry\Core;

use PDO;

class ORM {
  private $db;

  public function __construct() {
    $this->db = new PDO('mysql:host=localhost;dbname=mintberry;charset=utf8', 'root', '');
  }

  public function select($table, $columns = '*', $where = null, $order = null, $limit = null) {
    $sql = "SELECT $columns FROM $table";

    if ($where != null) $sql .= " WHERE $where";
    if ($order != null) $sql .= " ORDER BY $order";
    if ($limit != null) $sql .= " LIMIT $limit";

    $query = $this->db->prepare($sql);
    $query->execute();

    return $query->fetchAll(PDO::FETCH_OBJ);
  }

  public function insert($table, $columns, $values) {
    $sql = "INSERT INTO $table ($columns) VALUES ($values)";

    $query = $this->db->prepare($sql);
    $query->execute();
  }

  public function update($table, $columns, $where) {
    $sql = "UPDATE $table SET $columns WHERE $where";

    $query = $this->db->prepare($sql);
    $query->execute();
  }

  public function delete($table, $where) {
    $sql = "DELETE FROM $table WHERE $where";

    $query = $this->db->prepare($sql);
    $query->execute();
  }
}
