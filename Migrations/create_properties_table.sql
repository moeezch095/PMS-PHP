CREATE TABLE properties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    property_name VARCHAR(255) NOT NULL,
    property_type VARCHAR(255) NOT NULL,
    size VARCHAR(100),
    block VARCHAR(100),
    address TEXT,
    city VARCHAR(100),
    state VARCHAR(100),
    landline VARCHAR(50),
    country VARCHAR(100),

    manager_id INT NOT NULL,
    policy_id INT NOT NULL,
    company_id INT NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (manager_id) REFERENCES managers(id) ON DELETE CASCADE,
    FOREIGN KEY (policy_id) REFERENCES policies(id) ON DELETE CASCADE,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
);
