<?php

class Contract {

    private $pdo;
    private $table = "contracts";

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Get All
    public function findAll() {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table} ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get Single
    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create Contract
    public function create($data) {

        $data['created_at'] = date("Y-m-d H:i:s");

        $columns = implode(",", array_keys($data));
        $placeholders = ":" . implode(",:", array_keys($data));

        $stmt = $this->pdo->prepare("
            INSERT INTO {$this->table} ($columns)
            VALUES ($placeholders)
        ");

        $stmt->execute($data);

        // Return latest record
        $stmt = $this->pdo->prepare("
            SELECT * FROM {$this->table}
            WHERE tenant_id = :tenant_id
            ORDER BY id DESC
            LIMIT 1
        ");
        $stmt->execute([':tenant_id' => $data['tenant_id']]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update
    public function update($id, $data) {

        $set = "";
        foreach ($data as $key => $value) {
            $set .= "$key = :$key,";
        }
        $set = rtrim($set, ",");

        $data['id'] = $id;

        $stmt = $this->pdo->prepare("
            UPDATE {$this->table}
            SET $set
            WHERE id = :id
        ");

        return $stmt->execute($data);
    }

    // Delete
    public function delete($id) {
        $stmt = $this->pdo->prepare("
            DELETE FROM {$this->table} WHERE id = :id
        ");

        return $stmt->execute([':id' => $id]);
    }

    // Get Tenant Data
    public function getTenantData($tenantId) {
        $stmt = $this->pdo->prepare("
            SELECT id, tenant_name, trade_license_no, email,
                   passport_no, nationality, po_box, trn_no, contact_no
            FROM tenants
            WHERE id = :id LIMIT 1
        ");

        $stmt->execute([':id' => $tenantId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Join Contract + Tenant
    public function getContractWithTenant($contractId) {
        $stmt = $this->pdo->prepare("
            SELECT c.*, t.tenant_name, t.trade_license_no, t.email,
                   t.passport_no, t.nationality, t.po_box,
                   t.trn_no, t.contact_no
            FROM contracts c
            LEFT JOIN tenants t ON c.tenant_id = t.id
            WHERE c.id = :id LIMIT 1
        ");

        $stmt->execute([':id' => $contractId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
