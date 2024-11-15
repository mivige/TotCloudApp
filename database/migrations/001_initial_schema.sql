CREATE DATABASE totcloud_db;

USE totcloud_db;

CREATE TABLE User (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(56) NOT NULL,
    last_name VARCHAR(56) NOT NULL,
    last_name2 VARCHAR(56),
    mobile_phone VARCHAR(24) NOT NULL,
    email VARCHAR(128) NOT NULL UNIQUE,
    token VARCHAR(256),
    email_code VARCHAR(16),
    sms_code VARCHAR(16),
    password VARCHAR(256) NOT NULL,
    token_date DATETIME,
    active TINYINT(1) DEFAULT 1,  -- Indicates if the user is active (1 = active, 0 = inactive)
    validated_email TINYINT(1) DEFAULT 0,
    email_validation_attempts INT(11) DEFAULT 0,
    password_change_date DATETIME,
    password_change_request TINYINT(1) DEFAULT 0,
    login_attempts INT(11) DEFAULT 0,
    password_change_attempts INT(11) DEFAULT 0,
    password_change_request_date DATETIME,
    admin TINYINT(1) DEFAULT 0  -- Indicates if the user has admin privileges (1 = admin, 0 = not admin)
);

CREATE TABLE PasswordHistory (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    user_id INT(11) NOT NULL,  -- Foreign key referencing users
    password VARCHAR(256) NOT NULL,
    change_date DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES User(id) ON DELETE CASCADE  -- Ensures that history is deleted if the user is deleted
);

CREATE TABLE Role (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    role_name VARCHAR(56) NOT NULL UNIQUE,  -- Name of the role
    description VARCHAR(256)  -- Description of the role
);

CREATE TABLE UserRole (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    user_id INT(11) NOT NULL,  -- Foreign key referencing users
    role_id INT(11) NOT NULL,   -- Foreign key referencing roles
    FOREIGN KEY (user_id) REFERENCES User(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES Role(id) ON DELETE CASCADE
);

CREATE TABLE Permission (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    permission_name VARCHAR(56) NOT NULL UNIQUE,  -- Name of the permission
    description VARCHAR(256)  -- Description of the permission
);

CREATE TABLE RolePermission (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    role_id INT(11) NOT NULL,  -- Foreign key referencing roles
    permission_id INT(11) NOT NULL,  -- Foreign key referencing permissions
    FOREIGN KEY (role_id) REFERENCES Role(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES Permission(id) ON DELETE CASCADE,
    UNIQUE KEY (role_id, permission_id)  -- Prevent duplicate permission assignments for the same role
);


-- SAAS: Web Hosting
CREATE TABLE Datacenter (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(256) NOT NULL,
    location VARCHAR(256) NOT NULL,
    networkProvider VARCHAR(256) NOT NULL
);

CREATE TABLE SSL (
    id INT AUTO_INCREMENT PRIMARY KEY,
    provider VARCHAR(256) NOT NULL,
    validationLevel VARCHAR(256) NOT NULL,
    certificateType VARCHAR(256) NOT NULL,
    expirationDate DATETIME
);

CREATE TABLE DBMS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(256) NOT NULL,
    version VARCHAR(256) NOT NULL,
    licenseType VARCHAR(256) NOT NULL
);

CREATE TABLE Memory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    capacity INT NOT NULL,
    type VARCHAR(256) NOT NULL,
    speed INT NOT NULL
);

CREATE TABLE Persistency (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(256) NOT NULL
);

CREATE TABLE DB (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(256) NOT NULL,
    engine VARCHAR(256) NOT NULL,
    capacity INT NOT NULL,
    maxConnections INT NOT NULL,
    iops INT NOT NULL
    FK_DBMS INT NOT NULL,
    FK_memory INT NOT NULL,
    FK_persistency INT NOT NULL,
    FOREIGN KEY (FK_DBMS) REFERENCES DBMS(id),
    FOREIGN KEY (FK_memory) REFERENCES Memory(id),
    FOREIGN KEY (FK_persistency) REFERENCES Persistency(id)
);

CREATE TABLE WebHosting (
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
    FOREIGN KEY (FK_datacenter) REFERENCES Datacenter(id),
    FOREIGN KEY (FK_SSL) REFERENCES SSL(id),
    FOREIGN KEY (FK_DB) REFERENCES DB(id)
);

CREATE TABLE DNS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    domainName VARCHAR(256) NOT NULL,
    recordType VARCHAR(10) NOT NULL, -- A, AAAA, CNAME, etc.
    recordValue VARCHAR(256) NOT NULL,
    ttl INT NOT NULL,
    FK_webhosting INT NOT NULL,
    FOREIGN KEY (FK_webhosting) REFERENCES WebHosting(id)
);

CREATE TABLE Domain (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(256) NOT NULL,
    registrar VARCHAR(256),
    expirationDate DATETIME,
    isAutoRenew BOOLEAN DEFAULT FALSE,
    FK_webhosting INT NOT NULL,
    FOREIGN KEY (FK_webhosting) REFERENCES WebHosting(id)
);

CREATE TABLE Modules (
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
    FOREIGN KEY (FK_webhosting) REFERENCES WebHosting(id) ON DELETE CASCADE,
    FOREIGN KEY (FK_modules) REFERENCES Modules(id)
);

CREATE TABLE CDN (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(256) NOT NULL,
    endpoint VARCHAR(256) NOT NULL,
    cacheExpiration INT NOT NULL,
    bandwidth INT NOT NULL
);

CREATE TABLE CDNGeolocation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    region VARCHAR(256) NOT NULL,
    latency INT NOT NULL, -- in ms
    FK_CDN INT NOT NULL,
    FOREIGN KEY (FK_CDN) REFERENCES CDN(id) ON DELETE CASCADE
);

CREATE TABLE WebHostingXCDN (
    id INT AUTO_INCREMENT PRIMARY KEY,
    FK_webhosting INT NOT NULL,
    FK_CDN INT NOT NULL,
    FOREIGN KEY (FK_webhosting) REFERENCES WebHosting(id) ON DELETE CASCADE,
    FOREIGN KEY (FK_CDN) REFERENCES CDN(id)
);

CREATE TABLE ResourceUsage (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cpuUsage FLOAT NOT NULL, -- Percentage
    memoryUsage INT NOT NULL, -- in MB
    diskUsage INT NOT NULL, -- in MB
    bandwidthUsage INT NOT NULL, -- in MB
    timestamp DATETIME NOT NULL,
    FK_webhosting INT NOT NULL,
    FOREIGN KEY (FK_webhosting) REFERENCES WebHosting(id)
);

CREATE TABLE Logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    timestamp DATETIME NOT NULL,
    eventType VARCHAR(256) NOT NULL,
    details TEXT,
    FK_webhosting INT,
    FOREIGN KEY (FK_webhosting) REFERENCES WebHosting(id)
);
