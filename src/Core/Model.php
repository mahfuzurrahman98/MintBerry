<?php

namespace MintBerry\Core;

use MintBerry\Core\Database;


class Model {
  protected $db;
  protected $table;
  protected $hiddenColumns = [];
  protected $softDelete = false;


  public function __construct() {
    $this->db = Database::getInstance();
  }

  // create a new record
  public function create($data) {
    $columns = '';
    $namedValues = '';

    // check if it has a property called created_at
    if (!property_exists($data, 'created_at')) {
      $data->created_at = date('Y-m-d H:i:s');
    }

    foreach ($data as $key => $value) {
      $columns .= "$key,";
      $namedValues .= ":$key,";
    }

    $columns = rtrim($columns, ',');
    $namedValues = rtrim($namedValues, ',');

    $query = "INSERT INTO {$this->table}($columns) VALUES($namedValues)";

    $this->db->execute($query, $data);
    return $this->find($this->db->getLastInsertId());
  }


  public function all() {
    $stmt = $this->db->execute("SELECT * FROM {$this->table} WHERE deleted_at IS NULL");
    $data = $stmt->fetchAll(\PDO::FETCH_OBJ);

    // remove hidden columns
    foreach ($data as $key => $value) {
      foreach ($this->hiddenColumns as $hiddenColumn) {
        unset($data[$key]->$hiddenColumn);
      }
    }

    return $data;
  }


  public function find($id) {
    $stmt = $this->db->execute("SELECT * FROM {$this->table} WHERE id=:id AND deleted_at IS NULL", ['id' => $id]);
    $data = $stmt->fetch(\PDO::FETCH_OBJ);

    // remove hidden columns
    foreach ($this->hiddenColumns as $hiddenColumn) {
      unset($data->$hiddenColumn);
    }

    return $data;
  }


  public function update($id, $data) {
    // check if the table has a updated_at column
    if (!array_key_exists('updated_at', $data)) {
      $data['updated_at'] = date('Y-m-d H:i:s');
    }

    $setClause = '';
    foreach ($data as $key => $value) {
      $setClause .= "$key=:$key,";
    }
    $setClause = rtrim($setClause, ',');

    $query = "UPDATE {$this->table} SET $setClause WHERE id=:id";

    $data['id'] = $id;
    $stmt = $this->db->execute($query, $data);
    return $this->find($id);
  }


  public function delete($id) {
    if ($this->softDelete) {
      $stmt = $this->db->execute("UPDATE {$this->table} SET deleted_at = NOW() WHERE id=:id", ['id' => $id]);
      return $stmt->rowCount();
    } else {
      $stmt = $this->db->execute("DELETE FROM {$this->table} WHERE id=:id", ['id' => $id]);
      return $stmt->rowCount();
    }
  }
}
