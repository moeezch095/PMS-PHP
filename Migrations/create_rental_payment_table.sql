CREATE TABLE rental_payments (
    id INT AUTO_INCREMENT PRIMARY KEY,

    property_name VARCHAR(255) NOT NULL,
    unit_name VARCHAR(255) NOT NULL,
    owner_name VARCHAR(255) NOT NULL,
    tenant_name VARCHAR(255) NOT NULL,

    rent_amount DECIMAL(10,2) NOT NULL,
    overdue_amount DECIMAL(10,2) DEFAULT 0,
    amount_to_be_paid DECIMAL(10,2) NOT NULL,
    amount_paid DECIMAL(10,2) DEFAULT 0,

    starting_date DATE NOT NULL,
    next_due_date DATE NOT NULL,
    next_due VARCHAR(255) NULL,

    status VARCHAR(50) NOT NULL,          -- Paid / Unpaid / Partial
    mode_of_payment VARCHAR(50) NOT NULL, -- Cash / Cheque / DD
    installments INT DEFAULT 1,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
