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
