<?php
require_once __DIR__ . '/../Models/ManagerModel.php';
require_once __DIR__ . '/../Database/db.php';

class ManagerController {
    private $managerModel;

    public function __construct() {
        global $pdo;
        $this->managerModel = new Manager($pdo);
    }

    // Get all managers
    public function index() {
        $managers = $this->managerModel->findAll();
        echo json_encode([
            "message" => "Managers fetched successfully",
            "managers" => $managers
        ], JSON_PRETTY_PRINT);
    }

    // Get single manager
    public function show($id) {
        $manager = $this->managerModel->findById($id);
        if (!$manager) {
            http_response_code(404);
            echo json_encode(["message" => "Manager not found"], JSON_PRETTY_PRINT);
            return;
        }
        echo json_encode([
            "message" => "Manager fetched successfully",
            "manager" => $manager
        ], JSON_PRETTY_PRINT);
    }

    // Create manager
    public function store() {
        $data = json_decode(file_get_contents("php://input"), true);

        try {
            $freshManager = $this->managerModel->create($data);
            echo json_encode([
                "message" => "Manager created successfully",
                "manager" => $freshManager
            ], JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                "message" => $e->getMessage()
            ], JSON_PRETTY_PRINT);
        }
    }

    // Update manager
    public function update($id) {
        $data = json_decode(file_get_contents("php://input"), true);
        $exists = $this->managerModel->findById($id);
        if (!$exists) {
            http_response_code(404);
            echo json_encode(["message" => "Manager not found"], JSON_PRETTY_PRINT);
            return;
        }

        $updated = $this->managerModel->update($id, $data);
        echo json_encode([
            "message" => "Manager updated successfully",
            "manager" => $updated
        ], JSON_PRETTY_PRINT);
    }

    // Delete manager
    public function destroy($id) {
        $deleted = $this->managerModel->delete($id);
        if (!$deleted) {
            http_response_code(404);
            echo json_encode(["message" => "Manager not found"], JSON_PRETTY_PRINT);
            return;
        }

        echo json_encode([
            "message" => "Manager deleted successfully",
            "deleted_manager" => $deleted
        ], JSON_PRETTY_PRINT);
    }
}
