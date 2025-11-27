-- create_owners_table.sql
CREATE TABLE  owners (
  id INT AUTO_INCREMENT PRIMARY KEY,
  firstname VARCHAR(100) NOT NULL,
  lastname VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  trade_lic_no VARCHAR(100),
  address TEXT,           -- we store address as JSON string for simplicity
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
