<?php

class Tenant {
    private $pdo;
    private $table = "tenants";

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function insert($data) {
        $keys = array_keys($data);
        $fields = implode(",", $keys);
        $placeholders = ":" . implode(",:", $keys);

        $sql = "INSERT INTO {$this->table} ($fields) VALUES ($placeholders)";
        $query = $this->pdo->prepare($sql);
        $query->execute($data);

        return $this->pdo->lastInsertId();
    }

    public function update($id, $data) {
        $setFields = "";
        foreach ($data as $key => $value) {
            $setFields .= "$key = :$key,";
        }

        $setFields = rtrim($setFields, ",");

        $sql = "UPDATE {$this->table} SET $setFields WHERE id = :id";
        $data["id"] = $id;

        $query = $this->pdo->prepare($sql);
        return $query->execute($data);
    }

    public function get($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $query = $this->pdo->prepare($sql);
        $query->execute([$id]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function all() {
        $sql = "SELECT * FROM {$this->table} ORDER BY id DESC";
        $query = $this->pdo->query($sql);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id) {
        $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $query->execute([$id]);
    }
}
