-- A function that calculates the total CPU usage, memory usage, disk usage, etc., across all resource entries for a particular hosting or server.

DELIMITER $$

CREATE FUNCTION calculate_total_usage(p_web_hosting_id INT)
RETURNS DECIMAL(10,2)
BEGIN
    DECLARE total_usage DECIMAL(10,2);
    
    SELECT SUM(cpuUsage + memoryUsage + diskUsage + bandwidthUsage) 
    INTO total_usage
    FROM resource_usage
    WHERE web_hosting_id = p_web_hosting_id;
    
    RETURN total_usage;
END$$

DELIMITER ;
