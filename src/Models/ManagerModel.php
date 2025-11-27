<?php
require_once __DIR__ . '/../Database/db.php';

class Manager {
    protected $table = "managers";
    protected $pdo;

    public function __construct($pdo = null) {
        if (!$pdo) {
            global $pdo;
        }
        $this->pdo = $pdo;
    }

    // Get all managers
    public function findAll() {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} ORDER BY id DESC");
        $stmt->execute();
        $managers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($managers as &$manager) {
            if (isset($manager['address'])) {
                $manager['address'] = json_decode($manager['address'], true);
            }
        }
        return $managers;
    }

    // Get single manager
    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        $manager = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($manager && isset($manager['address'])) {
            $manager['address'] = json_decode($manager['address'], true);
        }
        return $manager;
    }

    // Create manager
    public function create($data) {
        // Email unique check
        if (isset($data['email'])) {
            $stmt = $this->pdo->prepare("SELECT id FROM {$this->table} WHERE email = ?");
            $stmt->execute([$data['email']]);
            if ($stmt->fetch()) {
                throw new Exception("Email already exists: " . $data['email']);
            }
        }

        if (isset($data['address'])) {
            $data['address'] = json_encode($data['address']);
        }

        $columns = implode(",", array_keys($data));
        $placeholders = implode(",", array_fill(0, count($data), "?"));
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} ($columns) VALUES ($placeholders)");
        $stmt->execute(array_values($data));

        return $this->findById($this->pdo->lastInsertId());
    }

    // Update manager
    public function update($id, $data) {
        if (isset($data['address'])) {
            $data['address'] = json_encode($data['address']);
        }

        $set = [];
        foreach ($data as $key => $val) {
            $set[] = "$key = ?";
        }
        $setString = implode(",", $set);

        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET $setString WHERE id = ?");
        $stmt->execute(array_merge(array_values($data), [$id]));

        return $this->findById($id);
    }

    // Delete manager
    public function delete($id) {
        $manager = $this->findById($id);
        if ($manager) {
            $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
            $stmt->execute([$id]);
        }
        return $manager;
    }
}









