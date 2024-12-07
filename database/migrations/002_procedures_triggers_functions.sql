USE totcloud_db;

-- ////////////////////
-- Functions
-- ////////////////////

-- A function that calculates the total CPU usage, memory usage, disk usage, etc., across all resource entries for a particular hosting or server.

DELIMITER $$

CREATE FUNCTION calculate_total_usage(p_web_hosting_id INT)
RETURNS DECIMAL(10,2)
READS SQL DATA
BEGIN
    DECLARE total_usage DECIMAL(10,2);
    
    SELECT SUM(cpuUsage + memoryUsage + diskUsage + bandwidthUsage) 
    INTO total_usage
    FROM resource_usage
    WHERE web_hosting_id = p_web_hosting_id;
    
    RETURN total_usage;
END$$

DELIMITER ;

-- This function returns the role name of a user.

DELIMITER $$

CREATE FUNCTION get_user_role(p_user_id INT) 
RETURNS VARCHAR(50)
READS SQL DATA
BEGIN
    DECLARE role_name VARCHAR(50);
    
    SELECT r.role_name
    INTO role_name
    FROM u_user_x_role ur
    JOIN u_role r ON ur.role_id = r.code
    WHERE ur.user_id = p_user_id
    LIMIT 1;
    
    RETURN role_name;
END$$

DELIMITER ;

-- ////////////////////
-- Triggers
-- ////////////////////

-- A trigger that inserts resource usage (e.g., CPU, memory, disk usage) whenever a new entry is made in the resource_usage table.

DELIMITER $$

CREATE TRIGGER log_resource_usage
AFTER INSERT ON resource_usage
FOR EACH ROW
BEGIN
    INSERT INTO logs (timestamp, eventType, details)
    VALUES (NEW.timestamp, 2, 
            CONCAT('CPU Usage: ', NEW.cpuUsage, '%, Memory Usage: ', NEW.memoryUsage, 'MB'));
END$$

DELIMITER ;

-- A trigger that logs the changes in the user table, such as password changes, for auditing purposes.

DELIMITER $$

CREATE TRIGGER log_user_changes
AFTER UPDATE ON user
FOR EACH ROW
BEGIN
    INSERT INTO logs (timestamp, eventType, details, user_id)
    VALUES (NOW(), 1, CONCAT('User ', OLD.email, ' updated'), OLD.id);
END$$

DELIMITER ;

-- A trigger to add an entry to the u_password_history table whenever the password of a user is updated.

DELIMITER $$

CREATE TRIGGER after_user_password_update
BEFORE UPDATE ON user
FOR EACH ROW
BEGIN
    IF OLD.password != NEW.password THEN
        INSERT INTO u_password_history (user_id, password, change_date)
        VALUES (OLD.id, NEW.password, NOW());
    END IF;
END$$

DELIMITER ;

-- ////////////////////
-- Procedures
-- ////////////////////

-- The Account Lock procedure ensures a user's account is locked after a certain number of failed login attempts (login_attempts) is exceeded. 
-- You can specify the threshold in the procedure.
-- Call this procedure after incrementing the login_attempts field when a login attempt fails:
-- CALL lock_user_account(1, 5); -- Locks user with ID 1 if failed attempts exceed 5.

DELIMITER $$

CREATE PROCEDURE lock_user_account(
    IN p_user_id INT,
    IN max_attempts INT
)
BEGIN
    DECLARE current_attempts INT;
    
    -- Get the current number of failed login attempts
    SELECT login_attempts INTO current_attempts
    FROM user
    WHERE id = p_user_id;

    -- Check if the failed attempts exceed the maximum allowed
    IF current_attempts >= max_attempts THEN
        -- Lock the account by setting 'activo' to 0
        UPDATE user
        SET active = 0
        WHERE id = p_user_id;

        -- Log the event
        INSERT INTO logs (timestamp, eventType, details)
        VALUES (NOW(), 3, CONCAT('User ID ', p_user_id, ' account locked due to too many failed attempts.'));
    END IF;
END$$

DELIMITER ;

-- A procedure for assigning a role to a user.

DELIMITER $$

CREATE PROCEDURE assign_role_to_user(
    IN p_user_id INT, IN p_role_code VARCHAR(20)
)
BEGIN
    INSERT INTO u_user_x_role (user_id, role_id)
    VALUES (p_user_id, p_role_code);
END$$

DELIMITER ;

-- A procedure to create a new request by a user.

DELIMITER $$

CREATE PROCEDURE create_request(
    IN p_user_id INT, IN p_state TINYINT
)
BEGIN
    INSERT INTO request (user_id, state, date)
    VALUES (p_user_id, p_state, NOW());
END$$

DELIMITER ;

-- A stored procedure to add a new user into the user table, including setting default values and potentially sending verification emails or SMS.

DELIMITER $$

CREATE PROCEDURE create_user(
    IN p_firstname VARCHAR(50), IN p_lastname VARCHAR(50), IN p_lastname2 VARCHAR(50),
    IN p_mobile_phone VARCHAR(20), IN p_email VARCHAR(100), IN p_password VARCHAR(256),
    IN p_token VARCHAR(256) , p_codigo_email VARCHAR(10), IN p_codigo_sms VARCHAR(10), IN p_fecha_token DATETIME
)
BEGIN
    INSERT INTO user (firstname, lastname, lastname2, mobile_phone, email, token, email_code, sms_code, password, token_date)
    VALUES (p_firstname, p_lastname, p_lastname2, p_mobile_phone, p_email, p_token, p_codigo_email, p_codigo_sms, p_password, p_fecha_token);
END$$

DELIMITER ;

-- A procedure to securely update a user’s password, ensuring that the password history is also updated.

DELIMITER $$

CREATE PROCEDURE update_user_password(
    IN p_user_id INT, IN p_new_password VARCHAR(256)
)
BEGIN
    -- DECLARE current_time DATETIME;
    -- SET current_time = NOW();

    -- Insert into password history
    INSERT INTO u_password_history (user_id, password, change_date)
    VALUES (p_user_id, p_new_password, NOW());

    -- Update the user table
    UPDATE user SET password = p_new_password, password_change_date = NOW()
    WHERE id = p_user_id;
END$$

DELIMITER ;

