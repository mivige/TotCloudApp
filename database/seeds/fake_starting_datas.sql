USE totcloud_db;

-- Disable foreign key checks to allow insertion
SET FOREIGN_KEY_CHECKS = 0;

-- Insert Roles
INSERT INTO u_role (role_name, description, code) VALUES
('Administrator', 'Full system access', 'ADMIN'),
('Sales Representative', 'Can create and manage requests', 'SALES'),
('Customer', 'Standard user with limited access', 'CUSTOMER');

-- Insert Users
INSERT INTO user (firstname, lastname, mobile_phone, email, password, active, email_verified, admin) VALUES
('John', 'Doe', '00015551234567', 'john.doe@example.com', SHA2('password123', 256), 1, 1, 0),
('Jane', 'Smith', '00015559876543', 'jane.smith@example.com', SHA2('securepass456', 256), 1, 1, 0),
('Mike', 'Admin', '0015551112222', 'mike.admin@totcloud.com', SHA2('adminpass789', 256), 1, 1, 1),
('Mitch', 'Kind', '00393899191006', 'mivige@totcloud.com', '$2a$12$SATm/uPgpTOHyearTXtOXOSMHtwGM4Z66QlRj1Chvs0QaoXXpzb7O', 1, 1, 1);

-- Link Users to Roles
INSERT INTO u_user_x_role (user_id, role_id) VALUES
(1, 'CUSTOMER'),
(2, 'SALES'),
(3, 'ADMIN'),
(4, 'ADMIN');

-- Insert event types
INSERT INTO event_types (id, name) VALUES
(1, 'User Updated'),
(2, 'Resource Usage Logged'),
(3, 'Account Locked');

-- Insert Datacenter Regions
INSERT INTO ds_datacenterregion (code, region_name, country, availability_zone, price) VALUES
('US-EAST', 'US East', 'United States', 'AZ1', 50.00),
('EU-WEST', 'Europe West', 'Ireland', 'AZ2', 60.00),
('ASIA-SOUTH', 'Asia South', 'Singapore', 'AZ3', 55.00);

-- Insert Memory Types
INSERT INTO ds_memory (code, description, capacity_gb, price) VALUES
('MEM-8GB', '8GB RAM', 8, 20.00),
('MEM-16GB', '16GB RAM', 16, 40.00),
('MEM-32GB', '32GB RAM', 32, 80.00);

-- Insert OS Types
INSERT INTO ds_os (code, name, version, price) VALUES
('UBUNTU-22', 'Ubuntu Linux', '22.04 LTS', 0.00),
('WINDOWS-22', 'Windows Server', '2022', 50.00),
('CENTOS-8', 'CentOS Linux', '8', 0.00);

-- Insert Processor Types
INSERT INTO ds_processor (code, description, cores, speed_ghz, price) VALUES
('PROC-4C', '4-Core Processor', 4, 2.5, 50.00),
('PROC-8C', '8-Core Processor', 8, 3.0, 100.00),
('PROC-16C', '16-Core Processor', 16, 3.5, 200.00);

-- Insert Storage Types
INSERT INTO ds_storage (code, description, capacity_gb, price) VALUES
('SSD-256', '256GB SSD', 256, 50.00),
('SSD-512', '512GB SSD', 512, 100.00),
('SSD-1TB', '1TB SSD', 1024, 200.00);

-- Insert Bandwidth Types
INSERT INTO ds_public_bandwidth (code, description, price) VALUES
('BW-10', '10 Mbps Public Bandwidth', 10.00),
('BW-100', '100 Mbps Public Bandwidth', 50.00),
('BW-1G', '1 Gbps Public Bandwidth', 200.00);

INSERT INTO ds_private_bandwidth (code, description, price) VALUES
('PRVBW-10', '10 Mbps Private Bandwidth', 5.00),
('PRVBW-100', '100 Mbps Private Bandwidth', 25.00),
('PRVBW-1G', '1 Gbps Private Bandwidth', 100.00);

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