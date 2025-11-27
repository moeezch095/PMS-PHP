<?php
require_once __DIR__ . '/../Models/Contract.php';
require_once __DIR__ . '/../Database/db.php';

class ContractController {

    private $contractModel;

    public function __construct($pdo = null) {

        // Fix: Always load working global PDO
        if ($pdo === null) {
            global $pdo;
        }

        // Assign global PDO into local variable
        $pdo = $pdo;

        // Init Model
        $this->contractModel = new Contract($pdo);
    }

    // Get All
    public function index() {
        $contracts = $this->contractModel->findAll();
        echo json_encode([
            "message" => "Contracts fetched successfully",
            "contracts" => $contracts
        ], JSON_PRETTY_PRINT);
    }

    // Get One
    public function show($id) {
        $contract = $this->contractModel->getContractWithTenant($id);

        if (!$contract) {
            http_response_code(404);
            echo json_encode(["message" => "Contract not found"]);
            return;
        }

        echo json_encode([
            "message" => "Contract fetched successfully",
            "contract" => $contract
        ], JSON_PRETTY_PRINT);
    }

    // Get Tenant Info
    public function getTenantInfo($tenantId) {
        $tenant = $this->contractModel->getTenantData($tenantId);

        if (!$tenant) {
            http_response_code(404);
            echo json_encode(["message" => "Tenant not found"]);
            return;
        }

        echo json_encode([
            "message" => "Tenant data fetched successfully",
            "tenant" => $tenant
        ], JSON_PRETTY_PRINT);
    }

    // Create Contract
    public function store() {

        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data['tenant_id'])) {
            http_response_code(400);
            echo json_encode(["message" => "tenant_id is required"]);
            return;
        }

        $tenantData = $this->contractModel->getTenantData($data['tenant_id']);

        if (!$tenantData) {
            http_response_code(404);
            echo json_encode(["message" => "Tenant not found"]);
            return;
        }

        // Auto-filled data
        $contractData = array_merge($data, [
            'tenant_name' => $tenantData['tenant_name'],
            'trade_license_no' => $tenantData['trade_license_no'],
            'email' => $tenantData['email'],
            'passport_no' => $tenantData['passport_no'],
            'nationality' => $tenantData['nationality'],
            'po_box' => $tenantData['po_box'],
            'trn_no' => $tenantData['trn_no'],
            'contact_no' => $tenantData['contact_no'],
        ]);

        $newContract = $this->contractModel->create($contractData);

        if (!$newContract) {
            http_response_code(500);
            echo json_encode(["message" => "Failed to create contract"]);
            return;
        }

        http_response_code(201);
        echo json_encode([
            "message" => "Contract created successfully",
            "contract" => $newContract
        ], JSON_PRETTY_PRINT);
    }

    // Update
    public function update($id) {
        $data = json_decode(file_get_contents("php://input"), true);

        $exists = $this->contractModel->findById($id);
        if (!$exists) {
            http_response_code(404);
            echo json_encode(["message" => "Contract not found"]);
            return;
        }

        $this->contractModel->update($id, $data);

        $updated = $this->contractModel->getContractWithTenant($id);

        echo json_encode([
            "message" => "Contract updated successfully",
            "contract" => $updated
        ], JSON_PRETTY_PRINT);
    }

    // Delete
    public function destroy($id) {
        $exists = $this->contractModel->findById($id);
        if (!$exists) {
            http_response_code(404);
            echo json_encode(["message" => "Contract not found"]);
            return;
        }

        $this->contractModel->delete($id);

        echo json_encode([
            "message" => "Contract deleted successfully",
            "deleted_contract" => $exists
        ], JSON_PRETTY_PRINT);
    }
}
