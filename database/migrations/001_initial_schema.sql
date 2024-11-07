CREATE DATABASE totcloud;

USE totcloud;

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
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE  -- Ensures that history is deleted if the user is deleted
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
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
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
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE,
    UNIQUE KEY (role_id, permission_id)  -- Prevent duplicate permission assignments for the same role
);
