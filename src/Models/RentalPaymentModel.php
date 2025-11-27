<?php
class RentalPaymentModel {
    private $pdo;
    private $table = "rental_payments";

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // ✅ CHECK IF NAME EXISTS IN TABLE
    private function exists($table, $column, $value) {
        $stmt = $this->pdo->prepare("SELECT 1 FROM {$table} WHERE {$column} = :val LIMIT 1");
        $stmt->execute([":val" => $value]);
        return $stmt->fetchColumn() ? true : false;
    }

    // ⭐ GET SINGLE PAYMENT BY ID
    public function get($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id=:id");
        $stmt->execute([":id" => $id]);
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$payment) return ["message"=>"Payment not found", "data"=>null];
        return ["message"=>"Payment added successfully", "data"=>$payment];
    }

    // ⭐ CREATE PAYMENT
    public function create($data) {
        // Validate required fields
        foreach (['property_name','unit_name','owner_name','tenant_name','rent_amount','amount_to_be_paid','starting_date','next_due_date','status','mode_of_payment'] as $key) {
            if (empty($data[$key])) return ["message"=>"$key is required", "data"=>null];
        }

        // ✅ Validate registered names
        if (!$this->exists('properties', 'property_name', $data['property_name'])) {
            return ["message"=>"Property not registered", "data"=>null];
        }
        if (!$this->exists('units', 'unit_name', $data['unit_name'])) {
            return ["message"=>"Unit not registered", "data"=>null];
        }
        if (!$this->exists('owners', 'firstname', explode(' ', $data['owner_name'])[0])) {
            return ["message"=>"Owner not registered", "data"=>null];
        }
        if (!$this->exists('tenants', 'tenant_name', $data['tenant_name'])) {
            return ["message"=>"Tenant not registered", "data"=>null];
        }

        // ✅ INSERT PAYMENT
        $sql = "INSERT INTO {$this->table} 
            (property_name, unit_name, owner_name, tenant_name, rent_amount, overdue_amount, amount_to_be_paid, amount_paid,
             starting_date, next_due_date, next_due, status, mode_of_payment, installments)
            VALUES
            (:property_name, :unit_name, :owner_name, :tenant_name, :rent_amount, :overdue_amount, :amount_to_be_paid, :amount_paid,
             :starting_date, :next_due_date, :next_due, :status, :mode_of_payment, :installments)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ":property_name" => $data["property_name"],
            ":unit_name" => $data["unit_name"],
            ":owner_name" => $data["owner_name"],
            ":tenant_name" => $data["tenant_name"],
            ":rent_amount" => $data["rent_amount"],
            ":overdue_amount" => $data["overdue_amount"] ?? 0,
            ":amount_to_be_paid" => $data["amount_to_be_paid"],
            ":amount_paid" => $data["amount_paid"] ?? 0,
            ":starting_date" => $data["starting_date"],
            ":next_due_date" => $data["next_due_date"],
            ":next_due" => $data["next_due"] ?? null,
            ":status" => $data["status"],
            ":mode_of_payment" => $data["mode_of_payment"],
            ":installments" => $data["installments"] ?? 1
        ]);

        $id = $this->pdo->lastInsertId();
        return $this->get($id); // Proper message: "Payment added successfully"
    }



    // ⭐ GET ALL (Only required fields)
    public function getAll()
    {
        $sql = "SELECT 
                    id,
                    tenant_name,
                    rent_amount,
                    overdue_amount,
                    amount_to_be_paid,
                    amount_paid,
                    next_due_date,
                    mode_of_payment,
                    status
                FROM rental_payments
                ORDER BY id DESC";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ⭐ FIND ONE
    public function find($id)
    {
        $sql = "SELECT 
                    id,
                    tenant_name,
                    rent_amount,
                    overdue_amount,
                    amount_to_be_paid,
                    amount_paid,
                    next_due_date,
                    mode_of_payment,
                    status
                FROM rental_payments
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ⭐ UPDATE
  public function update($id, $data) {
    // Default values agar keys missing ho
    $tenant_name     = $data['tenant_name'] ?? '';
    $rent_amount     = $data['rent_amount'] ?? '0.00';
    $overdue_amount  = $data['overdue_amount'] ?? null;
    $amount_to_be_paid = $data['amount_to_be_paid'] ?? '0.00';
    $amount_paid     = $data['amount_paid'] ?? null;
    $next_due_date   = $data['next_due_date'] ?? '0000-00-00';
    $mode_of_payment = $data['mode_of_payment'] ?? '';
    $status          = $data['status'] ?? '';

    // Update query
    $sql = "UPDATE rental_payments SET
            tenant_name = :tenant_name,
            rent_amount = :rent_amount,
            overdue_amount = :overdue_amount,
            amount_to_be_paid = :amount_to_be_paid,
            amount_paid = :amount_paid,
            next_due_date = :next_due_date,
            mode_of_payment = :mode_of_payment,
            status = :status
            WHERE id = :id";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
        ':tenant_name' => $tenant_name,
        ':rent_amount' => $rent_amount,
        ':overdue_amount' => $overdue_amount,
        ':amount_to_be_paid' => $amount_to_be_paid,
        ':amount_paid' => $amount_paid,
        ':next_due_date' => $next_due_date,
        ':mode_of_payment' => $mode_of_payment,
        ':status' => $status,
        ':id' => $id
    ]);

    // Response
    return [
        'message' => 'Record updated successfully',
        'data' => [
            'id' => $id,
            'tenant_name' => $tenant_name,
            'rent_amount' => $rent_amount,
            'overdue_amount' => $overdue_amount,
            'amount_to_be_paid' => $amount_to_be_paid,
            'amount_paid' => $amount_paid,
            'next_due_date' => $next_due_date,
            'mode_of_payment' => $mode_of_payment,
            'status' => $status
        ]
    ];
}


    // ⭐ DELETE
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM rental_payments WHERE id = :id");
        return $stmt->execute([":id" => $id]);
    }
}



