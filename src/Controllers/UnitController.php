<?php
require __DIR__ . "/../Models/Unit.php";

class UnitController {
    private $unit;
    public function __construct($pdo) {
        $this->unit = new Unit($pdo);
    }

    public function index() {
        return $this->jsonResponse($this->unit->getAll());
    }

    public function show($id) {
        return $this->jsonResponse($this->unit->get($id));
    }

    public function store() {
        $data = json_decode(file_get_contents("php://input"), true);
        return $this->jsonResponse($this->unit->create($data));
    }

    public function update($id) {
        $data = json_decode(file_get_contents("php://input"), true);
        return $this->jsonResponse($this->unit->update($id, $data));
    }

    public function destroy($id) {
        return $this->jsonResponse($this->unit->delete($id));
    }

    private function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }
}
