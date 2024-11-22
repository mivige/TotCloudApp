CREATE DATABASE totcloud_db;

USE totcloud_db;

-- ////////////////////
-- User
-- ////////////////////

CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    lastname2 VARCHAR(50),
    mobile_phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    token VARCHAR(256),
    codigo_email VARCHAR(10),
    codigo_sms VARCHAR(10),
    password VARCHAR(256) NOT NULL,
    fecha_token DATETIME,
    activo TINYINT(1) DEFAULT 0,
    validated_email TINYINT(1) DEFAULT 0,
    solicitud_cambio_password TINYINT(1) DEFAULT 0,
    numero_intentos_login INT DEFAULT 0,
    admin TINYINT(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE u_password_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    password VARCHAR(256) NOT NULL,
    change_date DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE u_role (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) NOT NULL UNIQUE,
    description VARCHAR(256),
    code VARCHAR(20) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE u_user_x_role (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    role_id VARCHAR(24) NOT NULL,
    UNIQUE (user_id, role_id),
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES u_role(code) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ////////////////////
-- Request
-- ////////////////////

CREATE TABLE request (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    date DATETIME NOT NULL,
    user_id INT NOT NULL,
    state TINYINT(1) DEFAULT 0, -- 1 Active, 0 Inactive
    FOREIGN KEY (user_id) REFERENCES user(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE category (
    id INT AUTO_INCREMENT,
    code VARCHAR(20) NOT NULL PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) DEFAULT 0.00,
    UNIQUE KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE currencytype (
    currency_code CHAR(3) NOT NULL PRIMARY KEY,
    currency_name VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE commitment_period (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) NOT NULL UNIQUE,
    description TINYTEXT,
    discount DECIMAL(10,2),
    currency_type CHAR(3),
    FOREIGN KEY (currency_type) REFERENCES currencytype(currency_code) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ////////////////////
-- PAAS: Dedicated Server
-- ////////////////////

CREATE TABLE ds_datacenterregion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) NOT NULL UNIQUE,
    region_name VARCHAR(50) NOT NULL,
    country VARCHAR(50),
    availability_zone VARCHAR(20),
    price DECIMAL(10,2),
    currency_type CHAR(3),
    description TINYTEXT,
    FOREIGN KEY (currency_type) REFERENCES currencytype(currency_code) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE paas_dedicated_server (
    id INT AUTO_INCREMENT PRIMARY KEY,
    price DECIMAL(10,2),
    category_code VARCHAR(20),
    public_bandwidth_code VARCHAR(20),
    private_bandwidth_code VARCHAR(20),
    storage_code VARCHAR(20),
    memory_code VARCHAR(20),
    processor_code VARCHAR(20),
    data_center_region_code VARCHAR(20),
    os_code VARCHAR(20),
    commitment_period VARCHAR(20),
    request_id INT NOT NULL,
    commitment_id INT,
    FOREIGN KEY (request_id) REFERENCES request(request_id) ON DELETE CASCADE,
    FOREIGN KEY (commitment_id) REFERENCES commitment_period(id) ON DELETE SET NULL,
    FOREIGN KEY (category_code) REFERENCES category(code) ON DELETE SET NULL,
    FOREIGN KEY (public_bandwidth_code) REFERENCES ds_public_bandwidth(code) ON DELETE SET NULL,
    FOREIGN KEY (private_bandwidth_code) REFERENCES ds_private_bandwidth(code) ON DELETE SET NULL,
    FOREIGN KEY (storage_code) REFERENCES ds_storage(code) ON DELETE SET NULL,
    FOREIGN KEY (memory_code) REFERENCES ds_memory(code) ON DELETE SET NULL,
    FOREIGN KEY (processor_code) REFERENCES ds_processor(code) ON DELETE SET NULL,
    FOREIGN KEY (data_center_region_code) REFERENCES ds_datacenterregion(code) ON DELETE SET NULL,
    FOREIGN KEY (os_code) REFERENCES ds_os(code) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE ds_memory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) NOT NULL UNIQUE,
    description TINYTEXT,
    capacity_gb INT,
    price DECIMAL(10,2),
    currency_type CHAR(3),
    FOREIGN KEY (currency_type) REFERENCES currencytype(currency_code) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE ds_os (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(50) NOT NULL,
    version VARCHAR(20),
    price DECIMAL(10,2),
    currency_type CHAR(3),
    FOREIGN KEY (currency_type) REFERENCES currencytype(currency_code) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE ds_private_bandwidth (
    id INT AUTO_INCREMENT UNIQUE,
    code VARCHAR(20) NOT NULL PRIMARY KEY,
    description TINYTEXT,
    price DECIMAL(10,2),
    currency_type CHAR(3),
    FOREIGN KEY (currency_type) REFERENCES currencytype(currency_code) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE ds_public_bandwidth (
    id INT AUTO_INCREMENT UNIQUE,
    code VARCHAR(20) NOT NULL PRIMARY KEY,
    description TINYTEXT,
    price DECIMAL(10,2),
    currency_type CHAR(3),
    FOREIGN KEY (currency_type) REFERENCES currencytype(currency_code) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE ds_processor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) NOT NULL UNIQUE,
    description TINYTEXT,
    cores INT,
    speed_ghz DECIMAL(5,2),
    price DECIMAL(10,2),
    currency_type CHAR(3),
    FOREIGN KEY (currency_type) REFERENCES currencytype(currency_code) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE ds_storage (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) NOT NULL UNIQUE,
    description TINYTEXT,
    capacity_gb INT,
    price DECIMAL(10,2),
    currency_type CHAR(3),
    FOREIGN KEY (currency_type) REFERENCES currencytype(currency_code) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ////////////////////
-- SAAS: Web Hosting
-- ////////////////////

CREATE TABLE wh_datacenter (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(256) NOT NULL,
    location VARCHAR(256) NOT NULL,
    networkProvider VARCHAR(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE wh_ssl (
    id INT AUTO_INCREMENT PRIMARY KEY,
    provider VARCHAR(256) NOT NULL,
    validationLevel VARCHAR(256) NOT NULL,
    certificateType VARCHAR(256) NOT NULL,
    expirationDate DATETIME
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE wh_db_dbms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(256) NOT NULL,
    version VARCHAR(256) NOT NULL,
    licenseType VARCHAR(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE wh_db_memory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    capacity INT NOT NULL,
    type VARCHAR(256) NOT NULL,
    speed INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE wh_db_persistency (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE wh_db (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(256) NOT NULL,
    engine VARCHAR(256) NOT NULL,
    capacity INT NOT NULL,
    maxConnections INT NOT NULL,
    iops INT NOT NULL
    FK_DBMS INT NOT NULL,
    FK_memory INT NOT NULL,
    FK_persistency INT NOT NULL,
    FOREIGN KEY (FK_DBMS) REFERENCES wh_db_dbms(id),
    FOREIGN KEY (FK_memory) REFERENCES wh_db_memory(id),
    FOREIGN KEY (FK_persistency) REFERENCES wh_db_persistency(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE saas_web_hosting (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(256) NOT NULL,
    
    storageSpace INT NOT NULL,
    bandwidthAllocation INT NOT NULL,
    maxConcurrentUsers INT NOT NULL,
    maxWebsites INT NOT NULL,
    isSSLIncluded BOOLEAN DEFAULT FALSE,
    isDomainIncluded BOOLEAN DEFAULT FALSE,
    isEmailIncluded BOOLEAN DEFAULT FALSE
    FK_datacenter INT NOT NULL,
    FK_SSL INT NOT NULL,
    FK_DB INT,
    request_id INT NOT NULL,
    commitment_id INT,
    FOREIGN KEY (request_id) REFERENCES request(request_id) ON DELETE CASCADE,
    FOREIGN KEY (commitment_id) REFERENCES commitment_period(id) ON DELETE SET NULL,
    FOREIGN KEY (FK_datacenter) REFERENCES wh_datacenter(id),
    FOREIGN KEY (FK_SSL) REFERENCES wh_ssl(id),
    FOREIGN KEY (FK_DB) REFERENCES wh_db(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE wh_dns (
    id INT AUTO_INCREMENT PRIMARY KEY,
    domainName VARCHAR(256) NOT NULL,
    recordType VARCHAR(10) NOT NULL, -- A, AAAA, CNAME, etc.
    recordValue VARCHAR(256) NOT NULL,
    ttl INT NOT NULL,
    FK_webhosting INT NOT NULL,
    FOREIGN KEY (FK_webhosting) REFERENCES saas_web_hosting(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE wh_domain (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(256) NOT NULL,
    registrar VARCHAR(256),
    expirationDate DATETIME NOT NULL,
    isAutoRenew BOOLEAN DEFAULT FALSE,
    FK_webhosting INT NOT NULL,
    FOREIGN KEY (FK_webhosting) REFERENCES saas_web_hosting(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE wh_modules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(256) NOT NULL,
    description TEXT NOT NULL,
    version VARCHAR(256) NOT NULL,
    isActive BOOLEAN DEFAULT FALSE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE wh_web_hosting_x_modules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    FK_webhosting INT NOT NULL,
    FK_modules INT NOT NULL,
    FOREIGN KEY (FK_webhosting) REFERENCES saas_web_hosting(id) ON DELETE CASCADE,
    FOREIGN KEY (FK_modules) REFERENCES wh_modules(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE wh_cdn (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(256) NOT NULL,
    endpoint VARCHAR(256) NOT NULL,
    cacheExpiration INT NOT NULL,
    bandwidth INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE wh_cdn_geolocation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    region VARCHAR(256) NOT NULL,
    latency INT NOT NULL, -- in ms
    FK_CDN INT NOT NULL,
    FOREIGN KEY (FK_CDN) REFERENCES wh_cdn(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE wh_web_hosting_x_cdn (
    id INT AUTO_INCREMENT PRIMARY KEY,
    FK_webhosting INT NOT NULL,
    FK_CDN INT NOT NULL,
    FOREIGN KEY (FK_webhosting) REFERENCES saas_web_hosting(id) ON DELETE CASCADE,
    FOREIGN KEY (FK_CDN) REFERENCES wh_cdn(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ////////////////////
-- Common tables
-- ////////////////////

CREATE TABLE resource_usage (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cpuUsage FLOAT NOT NULL, -- Percentage
    memoryUsage INT NOT NULL, -- in MB
    diskUsage INT NOT NULL, -- in MB
    bandwidthUsage INT NOT NULL, -- in MB
    timestamp DATETIME NOT NULL,
    web_hosting_id INT,
    dedicated_server_id INT,
    FOREIGN KEY (web_hosting_id) REFERENCES saas_web_hosting(id),
    FOREIGN KEY (dedicated_server_id) REFERENCES paas_dedicated_server(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    timestamp DATETIME NOT NULL,
    eventType VARCHAR(256) NOT NULL, -- Maybe create a table
    details TEXT,
    user_id INT,
    web_hosting_id INT,
    dedicated_server_id INT,
    FOREIGN KEY (user_id) REFERENCES user(id),
    FOREIGN KEY (web_hosting_id) REFERENCES saas_web_hosting(id),
    FOREIGN KEY (dedicated_server_id) REFERENCES paas_dedicated_server(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
