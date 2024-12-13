DELIMITER $$

CREATE TRIGGER log_web_hosting_changes AFTER UPDATE ON saas_web_hosting FOR EACH ROW BEGIN
    INSERT INTO logs (timestamp, eventType, details, web_hosting_id)
    VALUES (NOW(), 5, CONCAT('Web Hosting with Request ID ', NEW.request_id, ' modified'), NEW.id);
END$$

DELIMITER ;