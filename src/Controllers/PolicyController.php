<?php

require_once __DIR__ . '/../Models/PolicyModel.php';

class PolicyController
{
    private $policyModel;

    public function __construct()
    {
        global $pdo;
        $this->policyModel = new Policy($pdo);
    }

    // ---------- Get All ----------
    public function index()
    {
        $policies = $this->policyModel->findAll();

        echo json_encode([
            "message" => "Policies fetched successfully",
            "data" => $policies
        ]);
    }

    // ---------- Get Single ----------
    // public function show($id)
    // {
    //     $policy = $this->policyModel->findById($id);

    //     if (!$policy) {
    //         echo json_encode(["message" => "Policy not found"]);
    //         return;
    //     }

    //     echo json_encode([
    //         "message" => "Policy fetched successfully",
    //         "data" => $policy
    //     ]);
    // }

    // ---------- Create ----------
    public function store()
    {
        $name = $_POST['name'] ?? null;
        $terms = $_POST['terms'] ?? null;

        if (!$name || !$terms) {
            echo json_encode(["message" => "Name & Terms are required"]);
            return;
        }

        // File Upload
        $fileName = null;
        if (isset($_FILES['document']) && $_FILES['document']['error'] === 0) {
            $uploadDir = __DIR__ . '/../../Uploads/policies/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $fileName = time() . "_" . $_FILES['document']['name'];
            move_uploaded_file($_FILES['document']['tmp_name'], $uploadDir . $fileName);
        }

        $data = [
            "name" => $name,
            "terms" => $terms,
            "document" => $fileName
        ];

        $this->policyModel->create($data);

        // Get last inserted ID
        global $pdo;
        $id = $pdo->lastInsertId();

        // Fetch fresh row
        $policy = $this->policyModel->findById($id);

        echo json_encode([
            "message" => "Policy created successfully",
            "data" => $policy
        ]);
    }

    // ---------- Update ----------
  public function update($id)
{
    $policy = $this->policyModel->findById($id);

    if (!$policy) {
        echo json_encode(["message" => "Policy not found"]);
        return;
    }

    // Always use POST for update
    $name = $_POST['name'] ?? $policy['name'];
    $terms = $_POST['terms'] ?? $policy['terms'];
    $fileName = $policy['document'];

    // File upload handle
    if (isset($_FILES['document']) && $_FILES['document']['error'] === 0) {
        $uploadDir = __DIR__ . '/../../Uploads/policies/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $fileName = time() . "_" . $_FILES['document']['name'];
        move_uploaded_file($_FILES['document']['tmp_name'], $uploadDir . $fileName);
    }

    $data = [
        "name" => $name,
        "terms" => $terms,
        "document" => $fileName
    ];

    // Update in DB
    $this->policyModel->update($id, $data);

    // Fetch updated row
    $updated = $this->policyModel->findById($id);

    echo json_encode([
        "message" => "Policy updated successfully",
        "data" => $updated
    ]);
}
// ---------- Delete ----------
public function delete($id)
{
    // Pehle record fetch karo (delete se pehle)
    $policy = $this->policyModel->findById($id);

    if (!$policy) {
        echo json_encode([
            "message" => "Policy not found",
            "data" => null
        ]);
        return;
    }

    // Ab delete karo
    $this->policyModel->delete($id);

    echo json_encode([
        "message" => "Policy deleted successfully",
        "data" => $policy   // <-- Deleted policy return
    ]);
}
}
