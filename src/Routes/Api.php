<?php

use Bramus\Router\Router;

require __DIR__ . "/../Controllers/CompanyController.php";

require __DIR__ . "/../Controllers/TenantController.php";
require __DIR__ . "/../Controllers/ContractController.php";
require __DIR__ . "/../Controllers/OwnerController.php";
require __DIR__ . "/../Controllers/ManagerController.php";
require __DIR__ . "/../Controllers/PolicyController.php";
require __DIR__ . "/../Controllers/AmenitiesController.php";
require __DIR__ . "/../Controllers/PropertyController.php";
require __DIR__ . "/../Controllers/UnitController.php";
require_once __DIR__ . '/../Controllers/RentalPaymentController.php';



$controller = new ContractController($pdo);


$router = new Router();

// Company
$router->get('/companies', 'CompanyController@index');
$router->post('/companies', 'CompanyController@store');
$router->put('/companies/(\d+)', 'CompanyController@update');
$router->delete('/companies/(\d+)', 'CompanyController@delete');
$router->get('/companies/(\d+)', 'CompanyController@show');


// Property
$router->get('/properties', 'PropertyController@index');
$router->post('/properties', 'PropertyController@store');



// Contract (with file upload)
$router->get('/contracts', 'ContractController@index');
$router->post('/contracts', 'ContractController@store');
$router->delete('/contracts/(\d+)', 'ContractController@delete');


$router->get('/owners', 'OwnerController@index');        // ✅ Get all owners
$router->get('/owners/(\d+)', 'OwnerController@show');   // ✅ Get single owner by ID
$router->post('/owners', 'OwnerController@store');       // ✅ Create owner
$router->put('/owners/(\d+)', 'OwnerController@update'); // ✅ Update owner
$router->delete('/owners/(\d+)', 'OwnerController@destroy'); // ✅ Delete owner



// ✅ Manager Routes
$router->get('/managers', 'ManagerController@index');        // Get all managers
$router->get('/managers/(\d+)', 'ManagerController@show');   // Get single manager by ID
$router->post('/managers', 'ManagerController@store');       // Create manager
$router->put('/managers/(\d+)', 'ManagerController@update'); // Update manager
$router->delete('/managers/(\d+)', 'ManagerController@destroy'); // Delete manager

// Policy Routes
$router->get('/policies', 'PolicyController@index');          // Get all
// $router->get('/policies/(\d+)', 'PolicyController@show');     // Get single
$router->post('/policies', 'PolicyController@store');         // Create
$router->post('/policies/update/(\d+)', 'PolicyController@update');      // Update

$router->delete('/policies/(\d+)', 'PolicyController@delete'); // Delete



// PDO connection
require __DIR__ . "/../Database/db.php"; // yahan $conn define hoga

$amenitiesController = new AmenitiesController($pdo);


// Amenities Routes
$router->get('/amenities', 'AmenitiesController@index');         // Get all
$router->post('/amenities', 'AmenitiesController@store');         // Create
$router->put('/amenities/(\d+)', 'AmenitiesController@update');   // Update
$router->delete('/amenities/(\d+)', 'AmenitiesController@destroy'); // Delete


$propertyController = new PropertyController();

$router->get('/properties', 'PropertyController@index');          // Get all
$router->get('/properties/(\d+)', 'PropertyController@show');      // Get single
$router->post('/properties', 'PropertyController@store');         // Create
$router->put('/properties/(\d+)', 'PropertyController@update');   // Update
$router->delete('/properties/(\d+)', 'PropertyController@destroy'); // Delete


// Unit Routes (with $pdo)
$router->get('/units', function() use ($pdo) {
    $controller = new UnitController($pdo);
    $controller->index();
});

$router->get('/units/(\d+)', function($id) use ($pdo) {
    $controller = new UnitController($pdo);
    $controller->show($id);
});

$router->post('/units', function() use ($pdo) {
    $controller = new UnitController($pdo);
    $controller->store();
});

$router->put('/units/(\d+)', function($id) use ($pdo) {
    $controller = new UnitController($pdo);
    $controller->update($id);
});

$router->delete('/units/(\d+)', function($id) use ($pdo) {
    $controller = new UnitController($pdo);
    $controller->destroy($id);
});






$router->get('/tenants', function() use ($pdo) {
    (new TenantController($pdo))->index();
});

$router->get('/tenants/(\d+)', function($id) use ($pdo) {
    (new TenantController($pdo))->show($id);
});

$router->post('/tenants', function() use ($pdo) {
    (new TenantController($pdo))->store();
});

$router->post('/tenants/(\d+)', function($id) use ($pdo) {
    (new TenantController($pdo))->update($id);
});

$router->delete('/tenants/(\d+)', function($id) use ($pdo) {
    (new TenantController($pdo))->destroy($id);
});


// rentalPaymentRoutes

$router->post("/rental/create", function() use ($pdo) {
    $controller = new RentalPaymentController($pdo);
    $controller->store();
});



// Rental Payment Detail Routes

$router->get('/rental-payment-detail', function() use ($pdo) {
    $controller = new RentalPaymentController($pdo);
    $controller->index();
});

// UPDATE
// UPDATE (PUT route)
$router->put('/rental-payment-detail/update/(\d+)', function($id) use ($pdo) {
    $controller = new RentalPaymentController($pdo);
    $controller->update($id); // Controller me flexible update method handle karega POST/PUT
});


// DELETE
$router->delete ('/rental-payment-detail/delete/{id}', function($id) use ($pdo) {
    $controller = new RentalPaymentController($pdo);
    $controller->delete($id);
});






// Contract Routes

// Instantiate Controller
$contractController = new ContractController($pdo);





// ---------------------------
// CONTRACT ROUTES
// ---------------------------

// 1️⃣ Get All Contracts
$router->get("/contracts", "ContractController@index");

// 2️⃣ Get Single Contract (with tenant)
$router->get("/contracts/{id}", "ContractController@show");

// 3️⃣ Fetch tenant info for contract form
// Example: /contracts/tenant-info/3
$router->get("/contracts/tenant-info/{tenantId}", "ContractController@getTenantInfo");

// 4️⃣ Create Contract
$router->post('/contract/create', function() use($pdo) {
    $controller = new ContractController($pdo);
    $controller->store();
});


// 5️⃣ Update Contract
$router->put("/contracts/{id}", "ContractController@update");

// 6️⃣ Delete Contract
$router->delete("/contracts/{id}", "ContractController@destroy");


$router->run();


