-- contracts table structure
CREATE TABLE IF NOT EXISTS `contracts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  
  -- Tenant se fetch hone wali fields
  `tenant_id` INT NOT NULL,
  `tenant_name` VARCHAR(100),
  `trade_license_no` VARCHAR(50),
  `email` VARCHAR(150),
  `passport_no` VARCHAR(50),
  `nationality` VARCHAR(50),
  `po_box` VARCHAR(50),
  `trn_no` VARCHAR(50),
  `contact_no` VARCHAR(20),
  
  -- Property details (user fill karega)
  `property_name` VARCHAR(100),
  `unit_name` VARCHAR(50),
  `owner_name` VARCHAR(100),
  
  -- Lease details
  `lease_period_start` DATE,
  `lease_period_end` DATE,
  
  -- Payment details (user fill karega)
  `rent` DECIMAL(10,2) DEFAULT 0,
  `special_discount` DECIMAL(10,2) DEFAULT 0,
  `contract_value` DECIMAL(10,2) DEFAULT 0,
  `vat` DECIMAL(10,2) DEFAULT 0,
  `no_of_payment` INT DEFAULT 0,
  `mode_of_payment` ENUM('cash', 'cheque', 'bank') DEFAULT 'cash',
  
  -- Management Fee
  `management_fee_amount` DECIMAL(10,2) DEFAULT 0,
  `management_fee_date` DATE,
  
  -- Contract Charges
  `contract_charges_amount` DECIMAL(10,2) DEFAULT 0,
  `contract_charges_date` DATE,
  
  -- Security Deposit
  `security_deposit` DECIMAL(10,2) DEFAULT 0,
  
  -- Timestamps
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (`tenant_id`) REFERENCES `tenants`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*
📝 SAMAJH:
-----------
- tenant_id se tenant table se join karenge
- Baaki fields user manually fill karega
- FOREIGN KEY se relationship hai tenants table ke saath
*/