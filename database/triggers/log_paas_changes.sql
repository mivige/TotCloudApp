DELIMITER $$

CREATE TRIGGER log_dedicated_server_changes AFTER UPDATE ON paas_dedicated_server FOR EACH ROW BEGIN
    INSERT INTO logs (timestamp, eventType, details, dedicated_server_id)
    VALUES (NOW(), 6, CONCAT('Dedicated Server with Request ID ', NEW.request_id, ' modified'), NEW.id);
END$$

DELIMITER ;