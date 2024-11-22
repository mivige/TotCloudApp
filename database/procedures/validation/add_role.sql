-- A procedure for assigning a role to a user.

DELIMITER $$

CREATE PROCEDURE assign_role_to_user(
    IN p_user_id INT, IN p_role_code VARCHAR(20)
)
BEGIN
    INSERT INTO u_user_x_role (user_id, role_id)
    VALUES (p_user_id, p_role_code);
END$$

DELIMITER ;
