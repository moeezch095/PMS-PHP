CREATE TABLE IF NOT EXISTS units (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(255) NOT NULL,
    manager_name VARCHAR(255) NOT NULL,
    property_name VARCHAR(255) NOT NULL,
    unit_name VARCHAR(255) NOT NULL,
    amenities_name VARCHAR(255),
    block VARCHAR(50),
    area FLOAT,
    floor VARCHAR(50),
    owner_name VARCHAR(255) NOT NULL,
    tenant_name VARCHAR(255),
    bathrooms INT,
    status ENUM('vacant','renovate') DEFAULT 'vacant',
    add_property VARCHAR(255),
    add_block VARCHAR(255),
    add_floor VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
