-- A trigger that inserts resource usage (e.g., CPU, memory, disk usage) whenever a new entry is made in the resource_usage table.

DELIMITER $$

CREATE TRIGGER log_resource_usage
AFTER INSERT ON resource_usage
FOR EACH ROW
BEGIN
    INSERT INTO logs (timestamp, eventType, details)
    VALUES (NEW.timestamp, 'Resource Usage Logged', 
            CONCAT('CPU Usage: ', NEW.cpuUsage, '%, Memory Usage: ', NEW.memoryUsage, 'MB'));
END$$

DELIMITER ;
