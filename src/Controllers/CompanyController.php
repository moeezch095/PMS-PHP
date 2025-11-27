<?php
require __DIR__ . '/../Database/db.php';

class CompanyController {
public function index() {
    global $pdo;

    // Saari companies fetch karo
    $companies = $pdo->query("SELECT * FROM companies")->fetchAll(PDO::FETCH_ASSOC);

    // Remove unwanted line breaks from strings
    array_walk_recursive($companies, function (&$value) {
        $value = str_replace(["\n", "\r"], ' ', $value);
    });

    // JSON response
    $response = [
        "message" => "Companies fetched successfully",
        "data" => $companies
    ];

    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
}



public function show($id) {
    global $pdo;

    // Company fetch karo ID ke basis pe
    $stmt = $pdo->prepare("SELECT * FROM companies WHERE id = ?");
    $stmt->execute([$id]);
    $company = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$company) {
        // Company nahi mili
        header('Content-Type: application/json');
        echo json_encode([
            "message" => "Company not found",
            "company" => null
        ], JSON_PRETTY_PRINT);
        return;
    }

    // Clean line breaks in strings
    array_walk_recursive($company, function (&$value) {
        $value = str_replace(["\n", "\r"], ' ', $value);
    });

    // JSON response
    header('Content-Type: application/json');
    echo json_encode([
        "message" => "Company fetched successfully",
        "company" => $company
    ], JSON_PRETTY_PRINT);
}


public function store() {
    global $pdo;

    // JSON body parse karo
    $input = json_decode(file_get_contents("php://input"), true);

    // Fields le lo JSON se
    $name = $input['name'] ?? null;
    $lic_no = $input['lic_no'] ?? null;
    $companyHeader = $input['company_header'] ?? null;   // ye path hi DB me save hoga
    $companyUploads = $input['company_uploads'] ?? null; // ye path hi DB me save hoga

    // Validation (optional, simple)
    if (!$name) {
        header('Content-Type: application/json');
        echo json_encode(["message" => "Name is required"], JSON_PRETTY_PRINT);
        return;
    }

    // DB insert
    $stmt = $pdo->prepare("INSERT INTO companies (name, lic_no, company_header, company_uploads) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $name,
        $lic_no,
        $companyHeader,
        $companyUploads
    ]);

    // Fetch last inserted company
    $companyId = $pdo->lastInsertId();
    $stmt = $pdo->prepare("SELECT * FROM companies WHERE id = ?");
    $stmt->execute([$companyId]);
    $company = $stmt->fetch(PDO::FETCH_ASSOC);

    // Clean line breaks
    array_walk_recursive($company, function (&$value) {
        $value = str_replace(["\n", "\r"], ' ', $value);
    });

    // JSON response
    header('Content-Type: application/json');
    echo json_encode([
        "message" => "Company created successfully",
        "company" => $company
    ], JSON_PRETTY_PRINT);
}


public function update($id) {
    global $pdo;

    // JSON body parse karo
    $input = json_decode(file_get_contents("php://input"), true);

    // Pehle company fetch karo
    $stmt = $pdo->prepare("SELECT * FROM companies WHERE id = ?");
    $stmt->execute([$id]);
    $company = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$company) {
        header('Content-Type: application/json');
        echo json_encode(["message" => "Company not found"], JSON_PRETTY_PRINT);
        return;
    }

    // Update fields (agar JSON me nahi bheja gaya to purana value use karo)
    $name = $input['name'] ?? $company['name'];
    $lic_no = $input['lic_no'] ?? $company['lic_no'];
    $companyHeader = $input['company_header'] ?? $company['company_header'];
    $companyUploads = $input['company_uploads'] ?? $company['company_uploads'];

    // DB update
    $stmt = $pdo->prepare("
        UPDATE companies 
        SET name = ?, lic_no = ?, company_header = ?, company_uploads = ? 
        WHERE id = ?
    ");
    $stmt->execute([
        $name,
        $lic_no,
        $companyHeader,
        $companyUploads,
        $id
    ]);

    // Fetch updated company
    $stmt = $pdo->prepare("SELECT * FROM companies WHERE id = ?");
    $stmt->execute([$id]);
    $updatedCompany = $stmt->fetch(PDO::FETCH_ASSOC);

    // Clean line breaks
    array_walk_recursive($updatedCompany, function (&$value) {
        $value = str_replace(["\n", "\r"], ' ', $value);
    });

    // JSON response
    header('Content-Type: application/json');
    echo json_encode([
        "message" => "Company updated successfully",
        "company" => $updatedCompany
    ], JSON_PRETTY_PRINT);
}




public function delete($id) {
    global $pdo;

    // Pehle company fetch karo deleted data ke liye
    $stmt = $pdo->prepare("SELECT * FROM companies WHERE id=?");
    $stmt->execute([$id]);
    $company = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$company) {
        echo json_encode(["message" => "Company not found"]);
        return;
    }

    // Company delete karo
    $pdo->prepare("DELETE FROM companies WHERE id=?")->execute([$id]);

    // Clean line breaks in strings
    array_walk_recursive($company, function (&$value) {
        $value = str_replace(["\n", "\r"], ' ', $value);
    });

    header('Content-Type: application/json');


    echo json_encode([
        "message" => "Company deleted successfully",
        "company" => $company
    ]);
}
}