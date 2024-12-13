DELIMITER $$

CREATE TRIGGER log_request_changes AFTER INSERT ON request FOR EACH ROW BEGIN
    INSERT INTO logs (timestamp, eventType, details, user_id)
    VALUES (NOW(), 4, CONCAT('New request created with ID ', NEW.request_id), NEW.user_id);
END$$

DELIMITER ;