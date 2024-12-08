USE totcloud_db;

-- Disable foreign key checks to allow insertion
SET FOREIGN_KEY_CHECKS = 0;

-- Insert Roles
INSERT INTO u_role (role_name, description, code) VALUES
('Administrator', 'Full system access', 'ADMIN'),
('Sales Representative', 'Can create and manage requests', 'SALES'),
('Customer', 'Standard user with limited access', 'CUSTOMER');

-- Insert event types
INSERT INTO event_types (id, name) VALUES
(1, 'User Updated'),
(2, 'Resource Usage Logged'),
(3, 'Account Locked');

-- Insert request categories
INSERT INTO category VALUES 
(1, 'DDS001', 'Dedicated Private Server', 'Dedicated Private Server', 200.00),
(2, 'WHS001', 'Web Hosting Start', 'Para los primeros pasos online', 12.00),
(3, 'WHP002', 'Web Hosting Personal', 'Para crear un sitio web o un blog', 36.00),
(4, 'WHP003', 'Web Hosting Professionals', 'Para sitios web profesionales', 72.00),
(5, 'WHP004', 'Web Hosting Performance', 'Para proyectos multisitio y tiendas online', 132.00);

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

INSERT INTO wh_ssl (provider, validationLevel, certificateType, expirationDate, price) VALUES
('Lets Encrypt', 'Domain Validation', 'Free', '2024-12-31 23:59:59', 0.00),
('GlobalSign', 'Extended Validation', 'Business', '2025-06-30 23:59:59', 10,00);

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;