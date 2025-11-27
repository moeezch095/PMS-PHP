<?php
class Unit {
    private $pdo;
    private $table = "units";

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // ⭐ COMMON RESPONSE FORMAT FUNCTION
    private function formatUnit($u) {
        return [
            "id" => $u["id"],
            "company" => $u["company_name"],
            "manager" => $u["manager_name"],
            "property" => $u["property_name"],
            "unit_name" => $u["unit_name"],
            "amenities" => $u["amenities_name"] ?? null,
            "block" => $u["block"],
            "area" => $u["area"] ? $u["area"] . " sqft" : null,
            "floor" => $u["floor"],
            "owner" => $u["owner_name"],
            "tenant" => $u["tenant_name"] ?? null,
            "bathrooms" => $u["bathrooms"] ?? null,
            "status" => ucfirst($u["status"]),
            "add_property" => $u["add_property"] ?? null,
            "add_block" => $u["add_block"] ?? null,
            "add_floor" => $u["add_floor"] ?? null
        ];
    }

    // ✅ CHECK IF VALUE EXISTS IN TABLE
    private function exists($table, $column, $value) {
        $stmt = $this->pdo->prepare("SELECT id FROM {$table} WHERE {$column} = :val LIMIT 1");
        $stmt->execute([":val" => $value]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    // ✅ CHECK IF MANAGER EXISTS (firstname + lastname)
    private function managerExists($fullname) {
        $parts = explode(' ', $fullname);
        $firstname = $parts[0] ?? '';
        $lastname = $parts[1] ?? '';
        $stmt = $this->pdo->prepare("SELECT id FROM managers WHERE firstname = :fname AND lastname = :lname LIMIT 1");
        $stmt->execute([":fname"=>$firstname, ":lname"=>$lastname]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    // ✅ CHECK IF OWNER EXISTS (firstname + lastname)
    private function ownerExists($fullname) {
        $parts = explode(' ', $fullname);
        $firstname = $parts[0] ?? '';
        $lastname = $parts[1] ?? '';
        $stmt = $this->pdo->prepare("SELECT id FROM owners WHERE firstname = :fname AND lastname = :lname LIMIT 1");
        $stmt->execute([":fname"=>$firstname, ":lname"=>$lastname]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    // ⭐ CREATE UNIT
    public function create($data) {
        // ✅ Owner handling
        if (!empty($data['owner_firstname']) || !empty($data['owner_lastname'])) {
            $data['owner'] = trim(($data['owner_firstname'] ?? '') . ' ' . ($data['owner_lastname'] ?? ''));
        } elseif (!empty($data['owner'])) {
            $data['owner'] = $data['owner'];
        } else {
            return ["message"=>"owner is required"];
        }

        // ✅ Validate required fields
        foreach (['company','manager','property','owner'] as $key) {
            if (empty($data[$key])) return ["message"=>"$key is required"];
        }

        // ✅ Check if these are registered already
        if (!$this->exists('companies', 'name', $data['company'])) return ["message"=>"Company not registered"];
        if (!$this->managerExists($data['manager'])) return ["message"=>"Manager not registered"];
        if (!$this->exists('properties', 'property_name', $data['property'])) return ["message"=>"Property not registered"];
        if (!$this->ownerExists($data['owner'])) return ["message"=>"Owner not registered"];
        if (!empty($data['amenities']) && !$this->exists('amenities', 'add_amenities', $data['amenities'])) return ["message"=>"Amenities not registered"];

        // ✅ INSERT UNIT
        $sql = "INSERT INTO {$this->table} 
            (company_name, manager_name, property_name, unit_name, amenities_name, block, area, floor, owner_name, tenant_name, bathrooms, status, add_property, add_block, add_floor)
            VALUES
            (:company, :manager, :property, :unit_name, :amenities, :block, :area, :floor, :owner, :tenant, :bathrooms, :status, :add_property, :add_block, :add_floor)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ":company" => $data["company"],
            ":manager" => $data["manager"],
            ":property" => $data["property"],
            ":unit_name" => $data["unit_name"],
            ":amenities" => $data["amenities"] ?? null,
            ":block" => $data["block"] ?? null,
            ":area" => $data["area"] ?? null,
            ":floor" => $data["floor"] ?? null,
            ":owner" => $data["owner"],
            ":tenant" => $data["tenant"] ?? null,
            ":bathrooms" => $data["bathrooms"] ?? null,
            ":status" => $data["status"] ?? 'vacant',
            ":add_property" => $data["add_property"] ?? null,
            ":add_block" => $data["add_block"] ?? null,
            ":add_floor" => $data["add_floor"] ?? null
        ]);

        $id = $this->pdo->lastInsertId();

        return [
            "message" => "Unit added successfully",
            "data" => $this->get($id)["data"]
        ];
    }

    // ⭐ GET ALL UNITS
    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY id DESC";
        $stmt = $this->pdo->query($sql);
        $units = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $formatted = [];
        foreach($units as $u) {
            $formatted[] = $this->formatUnit($u);
        }

        return [
            "message" => "Units fetched successfully",
            "data" => $formatted
        ];
    }

    // ⭐ GET SINGLE UNIT
    public function get($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id=:id");
        $stmt->execute([":id"=>$id]);
        $u = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$u) return ["message"=>"Unit not found", "data"=>null];

        return [
            "message" => "Unit fetched",
            "data" => $this->formatUnit($u)
        ];
    }

    // ⭐ UPDATE UNIT
    public function update($id, $data) {
        // ✅ Owner handling
        if (!empty($data['owner_firstname']) || !empty($data['owner_lastname'])) {
            $data['owner'] = trim(($data['owner_firstname'] ?? '') . ' ' . ($data['owner_lastname'] ?? ''));
        } elseif (!empty($data['owner'])) {
            $data['owner'] = $data['owner'];
        } else {
            return ["message"=>"owner is required"];
        }

        // Check exists
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id=:id");
        $stmt->execute([":id"=>$id]);
        if(!$stmt->fetch(PDO::FETCH_ASSOC)) return ["message"=>"Unit not found", "data"=>null];

        // ✅ Validate registered references
        if (!$this->exists('companies', 'name', $data['company'])) return ["message"=>"Company not registered"];
        if (!$this->managerExists($data['manager'])) return ["message"=>"Manager not registered"];
        if (!$this->exists('properties', 'property_name', $data['property'])) return ["message"=>"Property not registered"];
        if (!$this->ownerExists($data['owner'])) return ["message"=>"Owner not registered"];
        if (!empty($data['amenities']) && !$this->exists('amenities', 'add_amenities', $data['amenities'])) return ["message"=>"Amenities not registered"];

        $sql = "UPDATE {$this->table} SET
            company_name=:company,
            manager_name=:manager,
            property_name=:property,
            unit_name=:unit_name,
            amenities_name=:amenities,
            block=:block,
            area=:area,
            floor=:floor,
            owner_name=:owner,
            tenant_name=:tenant,
            bathrooms=:bathrooms,
            status=:status,
            add_property=:add_property,
            add_block=:add_block,
            add_floor=:add_floor
            WHERE id=:id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ":company" => $data["company"],
            ":manager" => $data["manager"],
            ":property" => $data["property"],
            ":unit_name" => $data["unit_name"],
            ":amenities" => $data["amenities"] ?? null,
            ":block" => $data["block"] ?? null,
            ":area" => $data["area"] ?? null,
            ":floor" => $data["floor"] ?? null,
            ":owner" => $data["owner"],  
            ":tenant" => $data["tenant"] ?? null,
            ":bathrooms" => $data["bathrooms"] ?? null,
            ":status" => $data["status"] ?? 'vacant',
            ":add_property" => $data["add_property"] ?? null,
            ":add_block" => $data["add_block"] ?? null,
            ":add_floor" => $data["add_floor"] ?? null,
            ":id" => $id
        ]);

        return [
            "message" => "Unit updated successfully",
            "data" => $this->get($id)["data"]
        ];
    }

    // ⭐ DELETE UNIT
    public function delete($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id=:id");
        $stmt->execute([":id"=>$id]);
        if(!$stmt->fetch(PDO::FETCH_ASSOC)) return ["message"=>"Unit not found"];

        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id=:id");
        $stmt->execute([":id"=>$id]);

        return ["message"=>"Unit deleted successfully", "deleted_id"=>$id];
    }
}
