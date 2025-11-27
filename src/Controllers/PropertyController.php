<?php
require_once __DIR__ . '/../Models/Property.php';

class PropertyController {
    private $model;

    public function __construct() {
        global $pdo;
        $this->model = new Property($pdo);
    }

    public function index() {
        header('Content-Type: application/json');
        echo json_encode($this->model->getAll());
    }

    public function show($id) {
        header('Content-Type: application/json');
        echo json_encode($this->model->get($id));
    }

    public function store() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        echo json_encode($this->model->create($data));
    }

    public function update($id) {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        echo json_encode($this->model->update($id, $data));
    }

    public function destroy($id) {
        header('Content-Type: application/json');
        echo json_encode($this->model->delete($id));
    }
}
