-- A stored procedure to add a new user into the user table, including setting default values and potentially sending verification emails or SMS.

DELIMITER $$

CREATE PROCEDURE create_user(
    IN p_firstname VARCHAR(50), IN p_lastname VARCHAR(50), IN p_lastname2 VARCHAR(50),
    IN p_mobile_phone VARCHAR(20), IN p_email VARCHAR(100), IN p_password VARCHAR(256)
)
BEGIN
    INSERT INTO user (firstname, lastname, lastname2, mobile_phone, email, password)
    VALUES (p_firstname, p_lastname, p_lastname2, p_mobile_phone, p_email, p_password);
END$$

DELIMITER ;
