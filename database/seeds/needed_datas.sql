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
INSERT INTO ds_datacenterregion (code, region_name, country, availability_zone, price, description) VALUES
('EUFRRB001', 'Europe', 'France', 'AZ2', 0.00, 'Europe (France - Roubaix)  120s'),
('EUFRST001', 'Europe', 'France', 'AZ2', 0.00, 'Europe (France - Strasbourg)  120s'),
('EUFRGR001', 'Europe', 'France', 'AZ2', 0.00, 'Europe (France - Gravelines)  72h'),
('EUUKER001', 'Europe', 'United Kingdom', 'AZ2', 0.00, 'Europe (United Kingdom - Erith)  72h'),
('EUGELI001', 'Europe', 'Germany', 'AZ2', 0.00, 'Europe (Germany - Limburg)  72h');

-- Insert Memory Types
INSERT INTO ds_memory (code, description, capacity_gb, price) VALUES
('32GB001', '32GB DDR5 ECC On-Die 5200MHz1', 32, 0.00),
('64GB001', '64GB DDR5 ECC On-Die 5200MHz', 64, 10.00),
('128GB001', '128GB DDR5 ECC On-Die 3600MHz', 128, 30.00),
('192GB001', '192GB DDR5 ECC On-Die 3600MHz', 192, 50.00);

-- Insert OS Types
INSERT INTO ds_os (code, name, version, price) VALUES
('UB24001', 'Ubuntu: Versión 24.04 LTS', '24.04 LTS', 0.00),
('RH93001', 'Red Hat Enterprise Linux (RHEL): Versión 9.3', '9.3', 0.00),
('CE9001', 'CentOS Stream: Versión 9', '9', 0.00),
('WS22001', 'Windows Server 2022', '2022', 50.00),
('AMLI23001', 'Amazon Linux 2023', '2023', 0.00);

-- Insert Processor Types
INSERT INTO ds_processor (code, description, cores, speed_ghz, price) VALUES
('AMDEP001', 'AMD EPYC 4244P - 6c/12t - 3.8GHz/5.1GHz', 6, 3.8, 0.00),
('INTXE001', 'Dual Intel Xeon Gold 5515+ - 16c/32t - 3.2GHz/3.6GHz', 16, 3.2, 150.00),
('AMDEP002', 'AMD EPYC GENOA 9454 - 48c/96t - 2.75GHz/3.65GHz', 48, 2.75, 200.00),
('INTXE002', 'Dual Intel Xeon Gold 6526Y - 32c/64t - 2.8GHz/3.5GHz', 32, 2.8, 300.00);

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
('10GBPIB001','10Gbit/s unmetered and guaranteed',0.00),
('25GBPIB001','25Gbit/s unmetered and guaranteed',100.00),
('50GBPIB001','50Gbit/s unmetered and guaranteed',200.00);

-- Insert Web Hosting related data
INSERT INTO wh_datacenter (name, location, networkProvider) VALUES
('Global Cloud DC1', 'United States', 'Tier1 Networks'),
('European Cloud DC', 'Ireland', 'EuroNet'),
('Asia Pacific DC', 'Singapore', 'AsiaLink');

INSERT INTO wh_ssl (provider, validationLevel, certificateType, expirationDate, price) VALUES
('Lets Encrypt', 'Domain Validation', 'Free', '2024-12-31 23:59:59', 0.00),
('GlobalSign', 'Extended Validation', 'Business', '2025-06-30 23:59:59', 10.00);

INSERT INTO wh_db_dbms (name, version, licenseType) VALUES
('DataWhizz', '3.2.1', 'GPL'),
('StoragePro', '1.8.0', 'MIT'),
('QueryMaster', '4.5.3', 'Apache-2.0'),
('InfoStacker', '2.3.4', 'Proprietary'),
('DBEase', '5.0.0', 'BSD');

INSERT INTO wh_db_persistency (type) VALUES
('Volatile'),
('Non-Volatile'),
('Temporary'),
('Long-Term'),
('Critical');

INSERT INTO wh_db_memory (capacity, type, speed) VALUES
(1024, 'SSD', 550),
(2048, 'HDD', 150),
(512, 'NVMe', 3500),
(8192, 'Hybrid', 300),
(256, 'RAMDisk', 7000);

INSERT INTO wh_modules (name, description, version, isActive) VALUES
('WordPress', 'A popular content management system for building websites and blogs.', '6.1.1', TRUE),
('WooCommerce', 'An eCommerce plugin for WordPress that allows online stores.', '7.4.2', TRUE),
('Yoast SEO', 'A WordPress plugin that helps optimize content for search engines.', '20.0.1', TRUE),
('phpMyAdmin', 'A free tool for managing MySQL databases via a web interface.', '5.2.0', FALSE),
('Mailchimp for WP', 'A plugin to integrate Mailchimp email marketing with WordPress.', '4.9.3', TRUE);

-- Insert commitment periods
INSERT INTO commitment_period VALUES 
(1,'NOCOM001','Without commitment',0.00),
(2,'6MCP001','6 months Save 5% per month on your server',5.00),
(3,'12MCP001','12 Months Save 10% per month on your server',10.00),
(4,'24MCP001','24 Months Save 15% per month on your server',15.00);

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;