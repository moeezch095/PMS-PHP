<?php
require_once __DIR__ . '/../Models/AmenitiesModel.php';

class AmenitiesController {
    private $model;

    public function __construct() {
        global $pdo;
        $this->model = new Amenities($pdo); // $pdo pass kar rahe hain
    }

    // Get all amenities
    public function index() {
        header('Content-Type: application/json');
        echo json_encode($this->model->getAll());
    }

    // Create amenity
    public function store() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        echo json_encode($this->model->create($data));
    }

    // Update amenity
    public function update($id) {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        echo json_encode($this->model->update($id, $data));
    }

 // Delete amenity
    public function destroy($id) {
        header('Content-Type: application/json');
        echo json_encode($this->model->delete($id)); // model ka delete function call karo
    }

}
