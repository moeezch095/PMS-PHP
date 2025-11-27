<?php

class Policy
{
    private $conn;
    private $table = "policies";

    public function __construct($pdo)
    {
        $this->conn = $pdo;
    }

    // -------- Get All --------
    public function findAll()
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // -------- Get Single --------
    // public function findById($id)
    // {
    //     $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
    //     $stmt->execute([$id]);
    //     return $stmt->fetch(PDO::FETCH_ASSOC);
    // }

    // -------- Create --------
    public function create($data)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO {$this->table}(name, terms, document)
            VALUES (:name, :terms, :document)
        ");

        return $stmt->execute([
            ":name" => $data['name'],
            ":terms" => $data['terms'],
            ":document" => $data['document']
        ]);
    }

    // -------- Update --------
    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("
            UPDATE {$this->table}
            SET name = :name, terms = :terms, document = :document
            WHERE id = :id
        ");

        return $stmt->execute([
            ":name" => $data['name'],
            ":terms" => $data['terms'],
            ":document" => $data['document'],
            ":id" => $id
        ]);
    }

    // -------- Delete --------
    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
