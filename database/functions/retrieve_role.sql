-- This function returns the role name of a user.

DELIMITER $$

CREATE FUNCTION get_user_role(p_user_id INT) 
RETURNS VARCHAR(50)
BEGIN
    DECLARE role_name VARCHAR(50);
    
    SELECT r.role_name
    INTO role_name
    FROM u_user_x_role ur
    JOIN u_role r ON ur.role_id = r.code
    WHERE ur.user_id = p_user_id
    LIMIT 1;
    
    RETURN role_name;
END$$

DELIMITER ;
