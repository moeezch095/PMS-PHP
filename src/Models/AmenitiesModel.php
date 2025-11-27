<?php
class Amenities {
    private $pdo;
    private $table = "amenities";

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Create Amenity
    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (add_amenities) VALUES (:add_amenities)");
        $stmt->bindValue(":add_amenities", $data['add_amenities']);
        if($stmt->execute()) {
            return [
                "message" => "Amenity added successfully",
                "data" => [
                    "id" => $this->pdo->lastInsertId(),
                    "add_amenities" => $data['add_amenities']
                ]
            ];
        }
        return ["message" => "Failed to add Amenity"];
    }

    // Get All Amenities
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table}");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return [
            "message" => "Amenities fetched successfully",
            "data" => $results
        ];
    }

    // Update Amenity
    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET add_amenities = :add_amenities WHERE id = :id");
        $stmt->bindValue(":add_amenities", $data['add_amenities']);
        $stmt->bindValue(":id", $id);
        if($stmt->execute()) {
            return [
                "message" => "Amenity updated successfully",
                "data" => [
                    "id" => $id,
                    "add_amenities" => $data['add_amenities']
                ]
            ];
        }
        return ["message" => "Failed to update Amenity"];
    }

  // AmenitiesModel.php
public function delete($id) {
    // Pehle fetch kar lo
    $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
    $stmt->bindValue(":id", $id);
    $stmt->execute();
    $amenity = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$amenity) {
        return ["message" => "Amenity not found"];
    }

    // Phir delete kar do
    $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
    $stmt->bindValue(":id", $id);
    if($stmt->execute()) {
        return [
            "message" => "Amenity deleted successfully",
            "data" => $amenity
        ];
    }

    return ["message" => "Failed to delete Amenity"];
}

}
