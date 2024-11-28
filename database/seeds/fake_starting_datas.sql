-- Disable foreign key checks to allow insertion
SET FOREIGN_KEY_CHECKS = 0;

-- Insert Currency Types
INSERT INTO currencytype (currency_code, currency_name) VALUES
('USD', 'United States Dollar'),
('EUR', 'Euro'),
('GBP', 'British Pound');

-- Insert Roles
INSERT INTO u_role (role_name, description, code) VALUES
('Administrator', 'Full system access', 'ADMIN'),
('Sales Representative', 'Can create and manage requests', 'SALES'),
('Customer', 'Standard user with limited access', 'CUSTOMER');

-- Insert Users
INSERT INTO user (firstname, lastname, mobile_phone, email, password, activo, validated_email, admin) VALUES
('John', 'Doe', '+1-555-123-4567', 'john.doe@example.com', SHA2('password123', 256), 1, 1, 0),
('Jane', 'Smith', '+1-555-987-6543', 'jane.smith@example.com', SHA2('securepass456', 256), 1, 1, 0),
('Mike', 'Admin', '+1-555-111-2222', 'mike.admin@totcloud.com', SHA2('adminpass789', 256), 1, 1, 1);

-- Link Users to Roles
INSERT INTO u_user_x_role (user_id, role_id) VALUES
(1, 'CUSTOMER'),
(2, 'SALES'),
(3, 'ADMIN');

-- Insert Datacenter Regions
INSERT INTO ds_datacenterregion (code, region_name, country, availability_zone, price, currency_type) VALUES
('US-EAST', 'US East', 'United States', 'AZ1', 50.00, 'USD'),
('EU-WEST', 'Europe West', 'Ireland', 'AZ2', 60.00, 'EUR'),
('ASIA-SOUTH', 'Asia South', 'Singapore', 'AZ3', 55.00, 'USD');

-- Insert Memory Types
INSERT INTO ds_memory (code, description, capacity_gb, price, currency_type) VALUES
('MEM-8GB', '8GB RAM', 8, 20.00, 'USD'),
('MEM-16GB', '16GB RAM', 16, 40.00, 'USD'),
('MEM-32GB', '32GB RAM', 32, 80.00, 'USD');

-- Insert OS Types
INSERT INTO ds_os (code, name, version, price, currency_type) VALUES
('UBUNTU-22', 'Ubuntu Linux', '22.04 LTS', 0.00, 'USD'),
('WINDOWS-22', 'Windows Server', '2022', 50.00, 'USD'),
('CENTOS-8', 'CentOS Linux', '8', 0.00, 'USD');

-- Insert Processor Types
INSERT INTO ds_processor (code, description, cores, speed_ghz, price, currency_type) VALUES
('PROC-4C', '4-Core Processor', 4, 2.5, 50.00, 'USD'),
('PROC-8C', '8-Core Processor', 8, 3.0, 100.00, 'USD'),
('PROC-16C', '16-Core Processor', 16, 3.5, 200.00, 'USD');

-- Insert Storage Types
INSERT INTO ds_storage (code, description, capacity_gb, price, currency_type) VALUES
('SSD-256', '256GB SSD', 256, 50.00, 'USD'),
('SSD-512', '512GB SSD', 512, 100.00, 'USD'),
('SSD-1TB', '1TB SSD', 1024, 200.00, 'USD');

-- Insert Bandwidth Types
INSERT INTO ds_public_bandwidth (code, description, price, currency_type) VALUES
('BW-10', '10 Mbps Public Bandwidth', 10.00, 'USD'),
('BW-100', '100 Mbps Public Bandwidth', 50.00, 'USD'),
('BW-1G', '1 Gbps Public Bandwidth', 200.00, 'USD');

INSERT INTO ds_private_bandwidth (code, description, price, currency_type) VALUES
('PRVBW-10', '10 Mbps Private Bandwidth', 5.00, 'USD'),
('PRVBW-100', '100 Mbps Private Bandwidth', 25.00, 'USD'),
('PRVBW-1G', '1 Gbps Private Bandwidth', 100.00, 'USD');

-- Insert Web Hosting related data
INSERT INTO wh_datacenter (name, location, networkProvider) VALUES
('Global Cloud DC1', 'United States', 'Tier1 Networks'),
('European Cloud DC', 'Ireland', 'EuroNet'),
('Asia Pacific DC', 'Singapore', 'AsiaLink');

INSERT INTO wh_ssl (provider, validationLevel, certificateType, expirationDate) VALUES
('Lets Encrypt', 'Domain Validation', 'Free', '2024-12-31 23:59:59'),
('GlobalSign', 'Extended Validation', 'Business', '2025-06-30 23:59:59');

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;