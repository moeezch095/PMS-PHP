<?php
class Property {
    private $pdo;
    private $table = "properties";

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($data) {

    /***
     * FIND MANAGER
     * Your input → "Ali Khan"
     * Split into firstname + lastname
     */
    $managerName = explode(" ", trim($data['manager']));
    $firstname = $managerName[0];
    $lastname = $managerName[1] ?? "";

    $stmt = $this->pdo->prepare("SELECT id FROM managers WHERE firstname = :firstname AND lastname = :lastname");
    $stmt->bindValue(":firstname", $firstname);
    $stmt->bindValue(":lastname", $lastname);
    $stmt->execute();
    $manager = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$manager) return ["message" => "Manager not found"];


    /***
     * FIND POLICY BY name COLUMN
     */
    $stmt = $this->pdo->prepare("SELECT id FROM policies WHERE name = :name");
    $stmt->bindValue(":name", $data['property_policy']);
    $stmt->execute();
    $policy = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$policy) return ["message" => "Policy not found"];


    /***
     * FIND COMPANY BY name COLUMN
     */
    $stmt = $this->pdo->prepare("SELECT id FROM companies WHERE name = :name");
    $stmt->bindValue(":name", $data['company']);
    $stmt->execute();
    $company = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$company) return ["message" => "Company not found"];


    // INSERT PROPERTY
    $stmt = $this->pdo->prepare("INSERT INTO {$this->table} 
        (property_name, property_type, size, block, address, city, state, landline, country, manager_id, policy_id, company_id) 
        VALUES 
        (:property_name, :property_type, :size, :block, :address, :city, :state, :landline, :country, :manager_id, :policy_id, :company_id)");

    $stmt->bindValue(":property_name", $data['property_name']);
    $stmt->bindValue(":property_type", $data['property_type']);
    $stmt->bindValue(":size", $data['size']);
    $stmt->bindValue(":block", $data['block']);
    $stmt->bindValue(":address", $data['address']);
    $stmt->bindValue(":city", $data['city']);
    $stmt->bindValue(":state", $data['state']);
    $stmt->bindValue(":landline", $data['landline']);
    $stmt->bindValue(":country", $data['country']);
    $stmt->bindValue(":manager_id", $manager['id']);
    $stmt->bindValue(":policy_id", $policy['id']);
    $stmt->bindValue(":company_id", $company['id']);

    if($stmt->execute()) {
        return [
            "message" => "Property added successfully",
            "data" => [
                "id" => $this->pdo->lastInsertId(),
                "property_name" => $data['property_name'],
                "property_type" => $data['property_type'],
                "size" => $data['size'],
                "block" => $data['block'],
                "address" => $data['address'],
                "city" => $data['city'],
                "state" => $data['state'],
                "landline" => $data['landline'],
                "country" => $data['country'],
                "manager" => $data['manager'],
                "property_policy" => $data['property_policy'],
                "company" => $data['company']
            ]
        ];
    }

    return ["message" => "Failed to add Property"];
}


    // Get All Properties
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table}");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return [
            "message" => "Properties fetched successfully",
            "data" => $results
        ];
    }

    // Get single property
    public function get($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $property = $stmt->fetch(PDO::FETCH_ASSOC);
        if($property) {
            return ["message" => "Property fetched successfully", "data" => $property];
        }
        return ["message" => "Property not found"];
    }

 public function update($id, $data) {
    // -------------------------
    // Lookup Manager ID by firstname + lastname
    // -------------------------
    $managerName = explode(" ", trim($data['manager']));
    $firstname = $managerName[0];
    $lastname = $managerName[1] ?? "";

    $stmt = $this->pdo->prepare("SELECT id FROM managers WHERE firstname = :firstname AND lastname = :lastname");
    $stmt->bindValue(":firstname", $firstname);
    $stmt->bindValue(":lastname", $lastname);
    $stmt->execute();
    $manager = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$manager) return ["message" => "Manager not found"];

    // -------------------------
    // Lookup Policy ID
    // -------------------------
    $stmt = $this->pdo->prepare("SELECT id FROM policies WHERE name = :name");
    $stmt->bindValue(":name", $data['property_policy']);
    $stmt->execute();
    $policy = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$policy) return ["message" => "Policy not found"];

    // -------------------------
    // Lookup Company ID
    // -------------------------
    $stmt = $this->pdo->prepare("SELECT id FROM companies WHERE name = :name");
    $stmt->bindValue(":name", $data['company']);
    $stmt->execute();
    $company = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$company) return ["message" => "Company not found"];

    // -------------------------
    // Update Property
    // -------------------------
    $stmt = $this->pdo->prepare("UPDATE {$this->table} SET 
        property_name = :property_name,
        property_type = :property_type,
        size = :size,
        block = :block,
        address = :address,
        city = :city,
        state = :state,
        landline = :landline,
        country = :country,
        manager_id = :manager_id,
        policy_id = :policy_id,
        company_id = :company_id
        WHERE id = :id");

    $stmt->bindValue(":property_name", $data['property_name']);
    $stmt->bindValue(":property_type", $data['property_type']);
    $stmt->bindValue(":size", $data['size']);
    $stmt->bindValue(":block", $data['block']);
    $stmt->bindValue(":address", $data['address']);
    $stmt->bindValue(":city", $data['city']);
    $stmt->bindValue(":state", $data['state']);
    $stmt->bindValue(":landline", $data['landline']);
    $stmt->bindValue(":country", $data['country']);
    $stmt->bindValue(":manager_id", $manager['id']);
    $stmt->bindValue(":policy_id", $policy['id']);
    $stmt->bindValue(":company_id", $company['id']);
    $stmt->bindValue(":id", $id);

    if($stmt->execute()) {
        return [
            "message" => "Property updated successfully",
            "data" => array_merge(["id"=>$id], $data)
        ];
    }

    return ["message" => "Failed to update Property"];
}

    // Delete property
    public function delete($id) {
        // Pehle fetch kar lo
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $property = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$property) return ["message" => "Property not found"];

        // Phir delete kar do
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindValue(":id", $id);
        if($stmt->execute()) return ["message"=>"Property deleted successfully","data"=>$property];

        return ["message"=>"Failed to delete Property"];
    }
}
