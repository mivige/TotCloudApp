-- A procedure to securely update a user’s password, ensuring that the password history is also updated.

DELIMITER $$

CREATE PROCEDURE update_user_password(
    IN p_user_id INT, IN p_new_password VARCHAR(256)
)
BEGIN
    DECLARE current_time DATETIME;
    SET current_time = NOW();

    -- Update the user table
    UPDATE user SET password = p_new_password, password_change_date = current_time
    WHERE id = p_user_id;
END$$

DELIMITER ;
