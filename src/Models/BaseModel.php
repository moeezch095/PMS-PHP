<?php

class BaseModel {
    protected $conn;
    protected $table;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all records
    public function findAll() {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get single record by ID
    public function findById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create new record
    public function create($data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} ($columns) VALUES ($placeholders)");
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        return $stmt->execute();
    }





    // Update record by ID
    public function update($id, $data) {
        $setStr = "";
        foreach ($data as $key => $value) {
            $setStr .= "$key = :$key, ";
        }
        $setStr = rtrim($setStr, ", ");
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET $setStr WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        return $stmt->execute();
    }

    // Delete record by ID
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
