<?php
require_once __DIR__ . "/../Models/RentalPaymentModel.php";

class RentalPaymentController {
    private $model;

    public function __construct($pdo) {
        $this->model = new RentalPaymentModel($pdo);
    }

    // ⭐ STORE PAYMENT
    public function store() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data) {
            echo json_encode(["message"=>"Invalid JSON", "data"=>null]);
            return;
        }

        $response = $this->model->create($data);
        echo json_encode($response, JSON_PRETTY_PRINT);
    }





  // ⭐ GET ALL
    public function index()
    {
        $data = $this->model->getAll();

        echo json_encode([
            "message" => "Rental payment list fetched",
            "data" => $data
        ], JSON_PRETTY_PRINT);
    }




public function update($id) {
    // Flexible data handling: POST ya PUT dono
    $input = file_get_contents("php://input");
    $data = json_decode($input, true); // JSON parse

    if (!$data && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = $_POST; // Agar POST form-data bheja
    }

    // Call model
    $result = $this->model->update($id, $data);

    // Response
    header('Content-Type: application/json');
    echo json_encode($result, JSON_PRETTY_PRINT);
}






    // ⭐ DELETE
    public function delete($id)
    {
        $exists = $this->model->find($id);

        if (!$exists) {
            echo json_encode(["message" => "Record not found"]);
            return;
        }

        $this->model->delete($id);

        echo json_encode([
            "message" => "Record deleted successfully"
        ], JSON_PRETTY_PRINT);
    }

}
