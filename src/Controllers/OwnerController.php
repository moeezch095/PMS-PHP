<?php
require_once __DIR__ . '/../Models/OwnerModel.php';

class OwnerController {

     // ✅ Get all owners

public function index()  {
    global $pdo;
    $ownerModel = new Owner($pdo);

    $owners = $ownerModel-> findAll();

    foreach ($owners as &$o ) {
$o['address'] = json_decode($o['address'], true );

    }
 
    header('Content-Type: application/json');
    echo json_encode ([
        "message" => "All owners fetched successfully ",
        "owners" => $owners
    ], JSON_PRETTY_PRINT);
    
}

// ✅ Get single owner by ID

public function show($id)  {
    global $pdo;
    $ownerModel = new Owner($pdo);

    $owner = $ownerModel->findById($id);

if(!$owner) {
    http_response_code (404);
    echo json_encode ([
        "message" => "Owner not found ",
        "owner" => null
    ], JSON_PRETTY_PRINT);
    return;
}
$owner['address'] = json_decode($owner['address'], true);

header('Content-Type: application/json');
echo json_encode([
    "message" => "Owner details fetched successfully",
    "owner" => $owner
], JSON_PRETTY_PRINT);

}


    // ✅ Create new owner

 public function store() {
    global $pdo;
    $ownerModel = new Owner($pdo);

    $data = json_decode(file_get_contents("php://input"), true);

    // Basic validation
    if (empty($data['firstname']) || empty($data['lastname']) || empty($data['email'])) {
        http_response_code(400);
        echo json_encode([
            "message" => "firstname, lastname and email are required"
        ], JSON_PRETTY_PRINT);
        return;
    }

    // Address ko JSON me convert karo agar diya gaya hai
    if (isset($data['address'])) {
        $data['address'] = json_encode($data['address']);
    }

    // Record create karo
    $created = $ownerModel->create($data);

    if ($created) {
        // ✅ Inserted owner ka ID lo
        $ownerId = $pdo->lastInsertId();

        http_response_code(201);
        echo json_encode([
            "message" => "Owner created successfully",
            "owner" => [
                "id" => $ownerId,  // <-- yah line nayi add hui hai
                "firstname" => $data['firstname'],
                "lastname" => $data['lastname'],
                "email" => $data['email'],
                "trade_lic_no" => $data['trade_lic_no'] ?? null,
                "address" => json_decode($data['address'], true)
            ]
        ], JSON_PRETTY_PRINT);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Failed to create owner"], JSON_PRETTY_PRINT);
    }
}

       // ✅ Update owner
       public function update($id)  {
        global $pdo;
        $ownerModel = new Owner ($pdo);

        $data = json_decode(file_get_contents("php://input"), true);

        if(isset ($data['address'])) {
            $data['address'] = json_encode($data['address']);
        }

        $updated = $ownerModel->update($id,$data);
        if  ($updated) {
            echo json_encode([
                "message" => "Owner updated successfully",
                "updated_owner" => [
                    "id" => $id,
                    "firstname" => $data['firstname'] ?? null,
                    "lastname" => $data['lastname'] ?? null,      
                     "email" => $data['email'] ?? null,
                    "trade_lic_no" => $data['trade_lic_no'] ?? null,
                    "address" => isset($data['address']) ? json_decode($data['address'], true) : null
                    
                    
                    ]
                ],JSON_PRETTY_PRINT);
        }else {
            http_response_code(500);
            echo json_encode(["message" => "Failed to update owner"], JSON_PRETTY_PRINT);
        }

       }

public function destroy($id) {
    global $pdo;
    $ownerModel = new Owner($pdo);

    // Pehle owner fetch karo
    $owner = $ownerModel->findById($id);

    if (!$owner) {
        http_response_code(404);
        echo json_encode(["message" => "Owner not found"], JSON_PRETTY_PRINT);
        return;
    }

    // Delete owner
    $deleted = $ownerModel->delete($id);

    if ($deleted) {
        // Address ko decode karo agar stringified JSON hai
        if (isset($owner['address'])) {
            $decodedAddress = json_decode($owner['address'], true);
            if ($decodedAddress) {
                $owner['address'] = $decodedAddress;
            }
        }

        echo json_encode([
            "message" => "Owner deleted successfully",
            "deleted_owner" => $owner
        ], JSON_PRETTY_PRINT);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Failed to delete owner"], JSON_PRETTY_PRINT);
    }
}

        }





       