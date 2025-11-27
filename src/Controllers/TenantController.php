<?php
require_once __DIR__ . '/../Models/Tenant.php';

class TenantController {
    private $model;

    public function __construct($pdo) {
        $this->model = new Tenant($pdo);
    }

    private function uploadFile($inputName) {
        if (!isset($_FILES[$inputName])) return null;

        $targetDir = "uploads/tenants/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $fileName = time() . "_" . $_FILES[$inputName]['name'];
        $filePath = $targetDir . $fileName;

        move_uploaded_file($_FILES[$inputName]['tmp_name'], $filePath);

        return $fileName;
    }

    public function store() {
        $data = $_POST;

        // File Handling
        $data['trade_license_file']      = $this->uploadFile('trade_license_file');
        $data['emirates_id_file']        = $this->uploadFile('emirates_id_file');
        $data['passport_id_file']        = $this->uploadFile('passport_id_file');
        $data['visa_document_file']      = $this->uploadFile('visa_document_file');

        $id = $this->model->insert($data);
        $tenant = $this->model->get($id);

        echo json_encode([
            "message" => "Tenant created successfully",
            "tenant" => $tenant
        ]);
    }

    public function update($id) {
        $data = $_POST;

        if (!empty($_FILES['trade_license_file']['name'])) 
            $data['trade_license_file'] = $this->uploadFile('trade_license_file');

        if (!empty($_FILES['emirates_id_file']['name'])) 
            $data['emirates_id_file'] = $this->uploadFile('emirates_id_file');

        if (!empty($_FILES['passport_id_file']['name'])) 
            $data['passport_id_file'] = $this->uploadFile('passport_id_file');

        if (!empty($_FILES['visa_document_file']['name'])) 
            $data['visa_document_file'] = $this->uploadFile('visa_document_file');

        $this->model->update($id, $data);
        $tenant = $this->model->get($id);

        echo json_encode([
            "message" => "Tenant updated successfully",
            "updated_tenant" => $tenant
        ]);
    }

    public function destroy($id) {
        $tenant = $this->model->get($id);
        $this->model->delete($id);

        echo json_encode([
            "message" => "Tenant deleted successfully",
            "deleted_tenant" => $tenant
        ]);
    }

    public function show($id) {
        echo json_encode($this->model->get($id));
    }

    public function index() {
        echo json_encode($this->model->all());
    }
}
