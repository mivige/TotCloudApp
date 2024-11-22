-- The Account Lock procedure ensures a user's account is locked after a certain number of failed login attempts (numero_intentos_login) is exceeded. 
-- You can specify the threshold in the procedure.

-- ///// USAGE //////
-- Call this procedure after incrementing the numero_intentos_login field when a login attempt fails:
-- CALL lock_user_account(1, 5); -- Locks user with ID 1 if failed attempts exceed 5.
-- ////////////////////

DELIMITER $$

CREATE PROCEDURE lock_user_account(
    IN p_user_id INT,
    IN max_attempts INT
)
BEGIN
    DECLARE current_attempts INT;
    
    -- Get the current number of failed login attempts
    SELECT numero_intentos_login INTO current_attempts
    FROM user
    WHERE id = p_user_id;

    -- Check if the failed attempts exceed the maximum allowed
    IF current_attempts >= max_attempts THEN
        -- Lock the account by setting 'activo' to 0
        UPDATE user
        SET activo = 0
        WHERE id = p_user_id;

        -- Log the event
        INSERT INTO logs (timestamp, eventType, details)
        VALUES (NOW(), 'Account Locked', CONCAT('User ID ', p_user_id, ' account locked due to too many failed attempts.'));
    END IF;
END$$

DELIMITER ;
