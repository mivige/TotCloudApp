CREATE DATABASE totcloud_db;

USE totcloud_db;

-- SAAS: Web Hosting
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
    CNAME 
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

CREATE TABLE WebHostingXModules (
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

CREATE TABLE WebHostingXCDN (
    id INT AUTO_INCREMENT PRIMARY KEY,
    FK_webhosting INT NOT NULL,
    FK_CDN INT NOT NULL,
    FOREIGN KEY (FK_webhosting) REFERENCES saas_web_hosting(id) ON DELETE CASCADE,
    FOREIGN KEY (FK_CDN) REFERENCES wh_cdn(id)
);

CREATE TABLE ResourceUsage (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cpuUsage FLOAT NOT NULL, -- Percentage
    memoryUsage INT NOT NULL, -- in MB
    diskUsage INT NOT NULL, -- in MB
    bandwidthUsage INT NOT NULL, -- in MB
    timestamp DATETIME NOT NULL,
    FK_webhosting INT NOT NULL,
    FOREIGN KEY (FK_webhosting) REFERENCES saas_web_hosting(id)
);

CREATE TABLE Logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    timestamp DATETIME NOT NULL,
    eventType VARCHAR(256) NOT NULL,
    details TEXT,
    FK_webhosting INT,
    FOREIGN KEY (FK_webhosting) REFERENCES saas_web_hosting(id)
);
