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
    numero_intentos_validated_email INT DEFAULT 0,
    fecha_cambio_password DATETIME,
    solicitud_cambio_password TINYINT(1) DEFAULT 0,
    numero_intentos_login INT DEFAULT 0,
    numero_intentos_cambio_password INT DEFAULT 0,
    fecha_solicitud_cambio_password DATETIME,
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

-- ////////////////////
-- PAAS: Dedicated Server
-- ////////////////////

CREATE TABLE `paas_ds_commitment_period` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `description` tinytext DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `currency_type` char(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `currency_type` (`currency_type`),
  CONSTRAINT `paas_ds_commitment_period_ibfk_1` FOREIGN KEY (`currency_type`) REFERENCES `currencytype` (`currency_code`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

CREATE TABLE `paas_ds_datacenterregion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `region_name` varchar(50) NOT NULL,
  `country` varchar(50) DEFAULT NULL,
  `availability_zone` varchar(20) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `currency_type` char(3) DEFAULT NULL,
  `description` tinytext DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `currency_type` (`currency_type`),
  CONSTRAINT `paas_ds_datacenterregion_ibfk_1` FOREIGN KEY (`currency_type`) REFERENCES `currencytype` (`currency_code`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

CREATE TABLE `paas_dedicated_server` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `price` decimal(10,2) DEFAULT NULL,
  `category_code` varchar(20) DEFAULT NULL,
  `public_bandwidth_code` varchar(20) DEFAULT NULL,
  `private_bandwidth_code` varchar(20) DEFAULT NULL,
  `storage_code` varchar(20) DEFAULT NULL,
  `memory_code` varchar(20) DEFAULT NULL,
  `processor_code` varchar(20) DEFAULT NULL,
  `data_center_region_code` varchar(20) DEFAULT NULL,
  `os_code` varchar(20) DEFAULT NULL,
  `commitment_period` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `category_code` (`category_code`),
  KEY `public_bandwidth_code` (`public_bandwidth_code`),
  KEY `private_bandwidth_code` (`private_bandwidth_code`),
  KEY `storage_code` (`storage_code`),
  KEY `memory_code` (`memory_code`),
  KEY `processor_code` (`processor_code`),
  KEY `data_center_region_code` (`data_center_region_code`),
  KEY `os_code` (`os_code`),
  CONSTRAINT `paas_dedicated_server_ibfk_1` FOREIGN KEY (`category_code`) REFERENCES `category` (`code`) ON DELETE SET NULL,
  CONSTRAINT `paas_dedicated_server_ibfk_2` FOREIGN KEY (`public_bandwidth_code`) REFERENCES `paas_public_bandwidth` (`code`) ON DELETE SET NULL,
  CONSTRAINT `paas_dedicated_server_ibfk_3` FOREIGN KEY (`private_bandwidth_code`) REFERENCES `paas_private_bandwidth` (`code`) ON DELETE SET NULL,
  CONSTRAINT `paas_dedicated_server_ibfk_4` FOREIGN KEY (`storage_code`) REFERENCES `paas_storage` (`code`) ON DELETE SET NULL,
  CONSTRAINT `paas_dedicated_server_ibfk_5` FOREIGN KEY (`memory_code`) REFERENCES `paas_memory` (`code`) ON DELETE SET NULL,
  CONSTRAINT `paas_dedicated_server_ibfk_6` FOREIGN KEY (`processor_code`) REFERENCES `paas_processor` (`code`) ON DELETE SET NULL,
  CONSTRAINT `paas_dedicated_server_ibfk_7` FOREIGN KEY (`data_center_region_code`) REFERENCES `paas_ds_datacenterregion` (`code`) ON DELETE SET NULL,
  CONSTRAINT `paas_dedicated_server_ibfk_8` FOREIGN KEY (`os_code`) REFERENCES `paas_os` (`code`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci 

CREATE TABLE `paas_ds_memory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `description` tinytext DEFAULT NULL,
  `capacity_gb` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `currency_type` char(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `currency_type` (`currency_type`),
  CONSTRAINT `paas_ds_memory_ibfk_1` FOREIGN KEY (`currency_type`) REFERENCES `currencytype` (`currency_code`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

CREATE TABLE `paas_ds_os` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `version` varchar(20) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `currency_type` char(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `currency_type` (`currency_type`),
  CONSTRAINT `paas_ds_os_ibfk_1` FOREIGN KEY (`currency_type`) REFERENCES `currencytype` (`currency_code`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci 

CREATE TABLE `paas_ds_private_bandwidth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `description` tinytext DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `currency_type` char(3) DEFAULT NULL,
  PRIMARY KEY (`code`),
  UNIQUE KEY `id` (`id`),
  KEY `currency_type` (`currency_type`),
  CONSTRAINT `paas_ds_private_bandwidth_ibfk_1` FOREIGN KEY (`currency_type`) REFERENCES `currencytype` (`currency_code`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

 CREATE TABLE `paas_ds_public_bandwidth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `description` tinytext DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `currency_type` char(3) DEFAULT NULL,
  PRIMARY KEY (`code`),
  UNIQUE KEY `id` (`id`),
  KEY `currency_type` (`currency_type`),
  CONSTRAINT `paas_ds_public_bandwidth_ibfk_1` FOREIGN KEY (`currency_type`) REFERENCES `currencytype` (`currency_code`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

CREATE TABLE `paas_ds_processor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `description` tinytext DEFAULT NULL,
  `cores` int(11) DEFAULT NULL,
  `speed_ghz` decimal(5,2) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `currency_type` char(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `currency_type` (`currency_type`),
  CONSTRAINT `paas_ds_processor_ibfk_1` FOREIGN KEY (`currency_type`) REFERENCES `currencytype` (`currency_code`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci 

CREATE TABLE `paas_ds_storage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `description` tinytext DEFAULT NULL,
  `capacity_gb` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `currency_type` char(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `currency_type` (`currency_type`),
  CONSTRAINT `paas_ds_storage_ibfk_1` FOREIGN KEY (`currency_type`) REFERENCES `currencytype` (`currency_code`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ////////////////////
-- SAAS: Web Hosting
-- ////////////////////

CREATE TABLE wh_datacenter (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(256) NOT NULL,
    location VARCHAR(256) NOT NULL,
    networkProvider VARCHAR(256) NOT NULL
);

CREATE TABLE wh_ssl (
    id INT AUTO_INCREMENT PRIMARY KEY,
    provider VARCHAR(256) NOT NULL,
    validationLevel VARCHAR(256) NOT NULL,
    certificateType VARCHAR(256) NOT NULL,
    expirationDate DATETIME
);

CREATE TABLE wh_db_dbms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(256) NOT NULL,
    version VARCHAR(256) NOT NULL,
    licenseType VARCHAR(256) NOT NULL
);

CREATE TABLE wh_db_memory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    capacity INT NOT NULL,
    type VARCHAR(256) NOT NULL,
    speed INT NOT NULL
);

CREATE TABLE wh_db_persistency (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(256) NOT NULL
);

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
);

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
    FOREIGN KEY (FK_datacenter) REFERENCES wh_datacenter(id),
    FOREIGN KEY (FK_SSL) REFERENCES wh_ssl(id),
    FOREIGN KEY (FK_DB) REFERENCES wh_db(id)
);

CREATE TABLE wh_dns (
    id INT AUTO_INCREMENT PRIMARY KEY,
    domainName VARCHAR(256) NOT NULL,
    recordType VARCHAR(10) NOT NULL, -- A, AAAA, CNAME, etc.
    recordValue VARCHAR(256) NOT NULL,
    ttl INT NOT NULL,
    FK_webhosting INT NOT NULL,
    FOREIGN KEY (FK_webhosting) REFERENCES saas_web_hosting(id)
);

CREATE TABLE wh_domain (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(256) NOT NULL,
    registrar VARCHAR(256),
    expirationDate DATETIME NOT NULL,
    isAutoRenew BOOLEAN DEFAULT FALSE,
    FK_webhosting INT NOT NULL,
    FOREIGN KEY (FK_webhosting) REFERENCES saas_web_hosting(id)
);

CREATE TABLE wh_modules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(256) NOT NULL,
    description TEXT NOT NULL,
    version VARCHAR(256) NOT NULL,
    isActive BOOLEAN DEFAULT FALSE
);

CREATE TABLE wh_web_hosting_x_modules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    FK_webhosting INT NOT NULL,
    FK_modules INT NOT NULL,
    FOREIGN KEY (FK_webhosting) REFERENCES saas_web_hosting(id) ON DELETE CASCADE,
    FOREIGN KEY (FK_modules) REFERENCES wh_modules(id)
);

CREATE TABLE wh_cdn (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(256) NOT NULL,
    endpoint VARCHAR(256) NOT NULL,
    cacheExpiration INT NOT NULL,
    bandwidth INT NOT NULL
);

CREATE TABLE wh_cdn_geolocation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    region VARCHAR(256) NOT NULL,
    latency INT NOT NULL, -- in ms
    FK_CDN INT NOT NULL,
    FOREIGN KEY (FK_CDN) REFERENCES wh_cdn(id) ON DELETE CASCADE
);

CREATE TABLE wh_web_hosting_x_cdn (
    id INT AUTO_INCREMENT PRIMARY KEY,
    FK_webhosting INT NOT NULL,
    FK_CDN INT NOT NULL,
    FOREIGN KEY (FK_webhosting) REFERENCES saas_web_hosting(id) ON DELETE CASCADE,
    FOREIGN KEY (FK_CDN) REFERENCES wh_cdn(id)
);

-- Possibly common to SaaS and PaaS
CREATE TABLE resource_usage (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cpuUsage FLOAT NOT NULL, -- Percentage
    memoryUsage INT NOT NULL, -- in MB
    diskUsage INT NOT NULL, -- in MB
    bandwidthUsage INT NOT NULL, -- in MB
    timestamp DATETIME NOT NULL,
    FK_webhosting INT NOT NULL,
    FOREIGN KEY (FK_webhosting) REFERENCES saas_web_hosting(id)
);

CREATE TABLE logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    timestamp DATETIME NOT NULL,
    eventType VARCHAR(256) NOT NULL,
    details TEXT,
    FK_webhosting INT,
    FOREIGN KEY (FK_webhosting) REFERENCES saas_web_hosting(id)
);
