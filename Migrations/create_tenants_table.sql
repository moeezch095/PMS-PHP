CREATE TABLE tenants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    property_name VARCHAR(255),
    unit_name VARCHAR(255),
    owner_name VARCHAR(255),

    tenant_name VARCHAR(255),
    trade_license_no VARCHAR(255),
    trade_license_noc VARCHAR(255),

    email VARCHAR(255),
    passport_no VARCHAR(255),
    nationality VARCHAR(255),
    po_box VARCHAR(255),
    trn_no VARCHAR(255),
    contact_no VARCHAR(255),

    trade_license_file VARCHAR(255),
    emirates_id_file VARCHAR(255),
    passport_id_file VARCHAR(255),
    visa_document_file VARCHAR(255),

    rent DECIMAL(10,2),
    vat DECIMAL(10,2),
    contract_charges DECIMAL(10,2),
    contract_payment_method VARCHAR(50),
    contract_payment_date DATE,

    management_fee DECIMAL(10,2),
    management_payment_method VARCHAR(50),
    management_payment_date DATE,

    security_deposit DECIMAL(10,2),
    chair_payment_method VARCHAR(50),
    chair_payment_date DATE,

    chair_charges DECIMAL(10,2),
    parking_payment_method VARCHAR(50),
    parking_payment_date DATE,

    parking_charges DECIMAL(10,2),
    office_maintenance_payment_method VARCHAR(50),
    office_maintenance_payment_date DATE,

    office_maintenance DECIMAL(10,2),
    additional_charges DECIMAL(10,2),
    additional_payment_method VARCHAR(50),
    additional_payment_date DATE,

    special_discount DECIMAL(10,2),
    net_payable DECIMAL(10,2),
    starting_date DATE,
    end_date DATE,
    cycle VARCHAR(10),
    month_wise INT,

    terms TEXT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
