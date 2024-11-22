-- A trigger that logs the changes in the user table, such as password changes, for auditing purposes.

DELIMITER $$

CREATE TRIGGER log_user_changes
AFTER UPDATE ON user
FOR EACH ROW
BEGIN
    INSERT INTO logs (timestamp, eventType, details, user_id)
    VALUES (NOW(), 'User Updated', CONCAT('User ', OLD.email, ' updated'), OLD.id);
END$$

DELIMITER ;
