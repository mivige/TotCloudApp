-- A procedure to create a new request by a user.

DELIMITER $$

CREATE PROCEDURE create_request(
    IN p_user_id INT, IN p_state TINYINT
)
BEGIN
    INSERT INTO request (user_id, state, date)
    VALUES (p_user_id, p_state, NOW());
END$$

DELIMITER ;
