-- ////////////////////
-- Incremental backup
-- ////////////////////

-- User Table Backup

    CREATE TABLE `user_backup_incremental` (
        `id` int(11) DEFAULT NULL,
        `firstname` varchar(50) DEFAULT NULL,
        `lastname` varchar(50) DEFAULT NULL,
        `lastname2` varchar(50) DEFAULT NULL,
        `mobile_phone` varchar(20) DEFAULT NULL,
        `email` varchar(100) DEFAULT NULL,
        `token` varchar(256) DEFAULT NULL,
        `email_code` varchar(10) DEFAULT NULL,
        `sms_code` varchar(10) DEFAULT NULL,
        `password` varchar(256) DEFAULT NULL,
        `token_date` datetime DEFAULT NULL,
        `active` tinyint(1) DEFAULT 0,
        `email_verified` tinyint(1) DEFAULT 0,
        `email_validation_attempts` int(11) DEFAULT 0,
        `password_change_date` datetime DEFAULT NULL,
        `password_change_request` tinyint(1) DEFAULT 0,
        `password_change_attempts` int(11) DEFAULT 0,
        `password_change_request_date` datetime DEFAULT NULL,
        `login_attempts` int(11) DEFAULT 0,
        `admin` tinyint(1) DEFAULT NULL,
        `operation_type` enum('INSERT','UPDATE','DELETE') NOT NULL,
        `operation_time` datetime DEFAULT current_timestamp()
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

    DELIMITER $$
    CREATE TRIGGER user_after_insert
    AFTER INSERT ON user
    FOR EACH ROW
    BEGIN
        INSERT INTO user_backup_incremental
        SELECT *, 'INSERT', NOW() FROM user WHERE id = NEW.id;
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE TRIGGER user_after_update
    AFTER UPDATE ON user
    FOR EACH ROW
    BEGIN
        -- Comprobar si hubo cambios en otros campos distintos de 'token' y 'token_date'
        IF NOT (
        NEW.firstname <=> OLD.firstname AND
        NEW.lastname <=> OLD.lastname AND
        NEW.lastname2 <=> OLD.lastname2 AND
        NEW.mobile_phone <=> OLD.mobile_phone AND
        NEW.email <=> OLD.email AND
        NEW.password <=> OLD.password AND
        NEW.active <=> OLD.active AND
        NEW.password_change_date <=> OLD.password_change_date AND
        NEW.admin <=> OLD.admin
        ) THEN
        -- Insertar en la tabla de respaldo si hay cambios en otros campos
        INSERT INTO user_backup_incremental
        SELECT *, 'UPDATE', NOW() FROM user WHERE id = NEW.id;
        END IF;
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE TRIGGER user_after_delete
    AFTER DELETE ON user
    FOR EACH ROW
    BEGIN
        INSERT INTO user_backup_incremental
        VALUES (
        OLD.id, OLD.firstname, OLD.lastname, OLD.lastname2,
        OLD.mobile_phone, OLD.email, OLD.token, OLD.email_code,
        OLD.sms_code, OLD.password, OLD.token_date, OLD.active,
        OLD.email_verified, OLD.email_validation_attempts,
        OLD.password_change_date, OLD.password_change_request,
        OLD.login_attempts, OLD.password_change_attempts,
        OLD.password_change_request_date, OLD.admin,
        'DELETE', NOW()
        );
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE TRIGGER user_before_delete
    BEFORE DELETE ON user
    FOR EACH ROW
    BEGIN
        -- Copiar registros relacionados de u_user_x_role antes de la eliminación
        INSERT INTO u_user_x_role_backup_incremental (id, user_id, role_id, operation_type, operation_time)
        SELECT 
        id, user_id, role_id, 'DELETE', NOW()
        FROM u_user_x_role
        WHERE user_id = OLD.id;
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE PROCEDURE export_to_file_user()
    BEGIN
        -- Construir la ruta del archivo dinámicamente
        SET @file_path = CONCAT('C:/mariadbbdss/segu/user_incremental_', DATE_FORMAT(NOW(), 
        '%Y%m%d%H%i%s'), '.csv');
        -- Construir el comando SQL dinámico
        SET @sql = CONCAT(
        'SELECT * FROM user_backup_incremental ',
        'INTO OUTFILE "', @file_path, '" ',
        'FIELDS TERMINATED BY "," ',
        'ENCLOSED BY \""\" ',
        'LINES TERMINATED BY "\\r\\n"'
        );
        -- Ejecutar el SQL dinámico
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
        -- Limpiar la tabla incremental
        DELETE FROM user_backup_incremental;
    END$$
    DELIMITER ;

-- Category table backup

    CREATE TABLE `category_backup_incremental` (
        `id` int(11) DEFAULT 0,
        `code` varchar(20) DEFAULT NULL,
        `name` varchar(50) DEFAULT NULL,
        `description` text DEFAULT NULL,
        `price` decimal(10,2) DEFAULT 0.00,
        `operation_type` enum('INSERT','UPDATE','DELETE') NOT NULL,
        `operation_time` datetime DEFAULT current_timestamp()
    ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

    DELIMITER $$
    CREATE TRIGGER category_after_insert
    AFTER INSERT ON category
    FOR EACH ROW
    BEGIN
        INSERT INTO category_backup_incremental
        SELECT *, 'INSERT', NOW() FROM category WHERE id = NEW.id;
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE TRIGGER category_after_update
    AFTER UPDATE ON category
    FOR EACH ROW
    BEGIN
        INSERT INTO category_backup_incremental
        SELECT *, 'UPDATE', NOW() FROM category WHERE id = NEW.id;
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE TRIGGER category_after_delete
    AFTER DELETE ON category
    FOR EACH ROW
    BEGIN
    INSERT INTO category_backup_incremental
    VALUES (
    OLD.id, OLD.code, OLD.name, OLD.description, OLD.price,
    'DELETE', NOW()
    );
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE PROCEDURE export_to_file_category()
    BEGIN
    -- Construir la ruta del archivo dinámicamente
    SET @file_path = CONCAT('C:/mariadbbdss/segu/category_incremental_', DATE_FORMAT(NOW(), '%Y%m%d%H%i%s'), '.csv');
    -- Construir el comando SQL dinámico
    SET @sql = CONCAT(
    'SELECT * FROM category_backup_incremental ',
    'INTO OUTFILE "', @file_path, '" ',
    'FIELDS TERMINATED BY "," ',
    'ENCLOSED BY \""\" ',
    'LINES TERMINATED BY "\\r\\n"'
    );
    -- Ejecutar el SQL dinámico
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    -- Limpiar la tabla incremental
    DELETE FROM category_backup_incremental;
    END$$
    DELIMITER ;

-- Commitment table backup

    CREATE TABLE `commitment_period_backup_incremental` (
    `id` int(11) DEFAULT 0,
    `code` varchar(20) DEFAULT NULL,
    `description` tinytext DEFAULT NULL,
    `discount` decimal(10,2) DEFAULT NULL,
    `operation_type` enum('INSERT','UPDATE','DELETE') NOT NULL,
    `operation_time` datetime DEFAULT current_timestamp()
    ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

    DELIMITER $$
    CREATE TRIGGER commitment_period_after_insert
    AFTER INSERT ON commitment_period
    FOR EACH ROW
    BEGIN
    INSERT INTO commitment_period_backup_incremental
    SELECT *, 'INSERT', NOW() FROM commitment_period WHERE id = NEW.id;
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE TRIGGER commitment_period_after_update
    AFTER UPDATE ON commitment_period
    FOR EACH ROW
    BEGIN
    INSERT INTO commitment_period_backup_incremental
    SELECT *, 'UPDATE', NOW() FROM commitment_period WHERE id = NEW.id;
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE TRIGGER commitment_period_after_delete
    AFTER DELETE ON commitment_period
    FOR EACH ROW
    BEGIN
    INSERT INTO commitment_period_backup_incremental
    VALUES (
    OLD.id, OLD.code, OLD.description, OLD.discount,
    'DELETE', NOW()
    );
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE PROCEDURE export_to_file_commitment_period()
    BEGIN
    -- Construir la ruta del archivo dinámicamente
    SET @file_path = CONCAT('C:/mariadbbdss/segu/commitment_period_incremental_', DATE_FORMAT(NOW(), '%Y%m%d%H%i%s'), '.csv');
    -- Construir el comando SQL dinámico
    SET @sql = CONCAT(
    'SELECT * FROM commitment_period_backup_incremental ',
    'INTO OUTFILE "', @file_path, '" ',
    'FIELDS TERMINATED BY "," ',
    'ENCLOSED BY \""\" ',
    'LINES TERMINATED BY "\\r\\n"'
    );
    -- Ejecutar el SQL dinámico
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    -- Limpiar la tabla incremental
    DELETE FROM commitment_period_backup_incremental;
    END$$
    DELIMITER ;

-- DataCenterRegion table backup

    CREATE TABLE `ds_datacenterregion_backup_incremental` (
    `id` int(11) DEFAULT 0,
    `code` varchar(20) DEFAULT NULL,
    `region_name` varchar(50) DEFAULT NULL,
    `country` varchar(50) DEFAULT NULL,
    `availability_zone` varchar(20) DEFAULT NULL,
    `price` decimal(10,2) DEFAULT NULL,
    `description` tinytext DEFAULT NULL,
    `operation_type` enum('INSERT','UPDATE','DELETE') NOT NULL,
    `operation_time` datetime DEFAULT current_timestamp()
    ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

    DELIMITER $$
    CREATE TRIGGER ds_datacenterregion_after_insert
    AFTER INSERT ON ds_datacenterregion
    FOR EACH ROW
    BEGIN
    INSERT INTO ds_datacenterregion_backup_incremental
    SELECT *, 'INSERT', NOW() FROM ds_datacenterregion WHERE id = NEW.id;
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE TRIGGER ds_datacenterregion_after_update
    AFTER UPDATE ON ds_datacenterregion
    FOR EACH ROW
    BEGIN
    INSERT INTO ds_datacenterregion_backup_incremental
    SELECT *, 'UPDATE', NOW() FROM ds_datacenterregion WHERE id = NEW.id;
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE TRIGGER ds_datacenterregion_after_delete
    AFTER DELETE ON ds_datacenterregion
    FOR EACH ROW
    BEGIN
    INSERT INTO ds_datacenterregion_backup_incremental
    VALUES (
    OLD.id, OLD.code, OLD.region_name, OLD.country, OLD.availability_zone,
    OLD.price, OLD.description,
    'DELETE', NOW()
    );
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE PROCEDURE export_to_file_ds_datacenterregion()
    BEGIN
    -- Construir la ruta del archivo dinámicamente
    SET @file_path = CONCAT('C:/mariadbbdss/segu/ds_datacenterregion_incremental_', 
    DATE_FORMAT(NOW(), '%Y%m%d%H%i%s'), '.csv');
    -- Construir el comando SQL dinámico
    SET @sql = CONCAT(
    'SELECT * FROM ds_datacenterregion_backup_incremental ',
    'INTO OUTFILE "', @file_path, '" ',
    'FIELDS TERMINATED BY "," ',
    'ENCLOSED BY \""\" ',
    'LINES TERMINATED BY "\\r\\n"'
    );
    -- Ejecutar el SQL dinámico
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    -- Limpiar la tabla incremental
    DELETE FROM ds_datacenterregion_backup_incremental;
    END$$
    DELIMITER ;

-- Memory table backup

    CREATE TABLE `ds_memory_backup_incremental` (
    `id` int(11) DEFAULT 0,
    `code` varchar(20) DEFAULT NULL,
    `description` tinytext DEFAULT NULL,
    `capacity_gb` int(11) DEFAULT NULL,
    `price` decimal(10,2) DEFAULT NULL,
    `operation_type` enum('INSERT','UPDATE','DELETE') NOT NULL,
    `operation_time` datetime DEFAULT current_timestamp()
    ) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

    DELIMITER $$
    CREATE TRIGGER ds_memory_after_insert
    AFTER INSERT ON ds_memory
    FOR EACH ROW
    BEGIN
    INSERT INTO ds_memory_backup_incremental
    SELECT *, 'INSERT', NOW() FROM ds_memory WHERE id = NEW.id;
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE TRIGGER ds_memory_after_update
    AFTER UPDATE ON ds_memory
    FOR EACH ROW
    BEGIN
    INSERT INTO ds_memory_backup_incremental
    SELECT *, 'UPDATE', NOW() FROM ds_memory WHERE id = NEW.id;
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE TRIGGER ds_memory_after_delete
    AFTER DELETE ON ds_memory
    FOR EACH ROW
    BEGIN
    INSERT INTO ds_memory_backup_incremental
    VALUES (
    OLD.id, OLD.code, OLD.description, OLD.capacity_gb, OLD.price,
    'DELETE', NOW()
    );
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE PROCEDURE export_to_file_ds_memory()
    BEGIN
    -- Construir la ruta del archivo dinámicamente
    SET @file_path = CONCAT('C:/mariadbbdss/segu/ds_memory_incremental_', DATE_FORMAT(NOW(), 
    '%Y%m%d%H%i%s'), '.csv');
    -- Construir el comando SQL dinámico
    SET @sql = CONCAT(
    'SELECT * FROM ds_memory_backup_incremental ',
    'INTO OUTFILE "', @file_path, '" ',
    'FIELDS TERMINATED BY "," ',
    'ENCLOSED BY \""\" ',
    'LINES TERMINATED BY "\\r\\n"'
    );
    -- Ejecutar el SQL dinámico
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    -- Limpiar la tabla incremental
    DELETE FROM ds_memory_backup_incremental;
    END$$
    DELIMITER ;

-- OS table backup

    CREATE TABLE `ds_os_backup_incremental` (
    `id` int(11) DEFAULT 0,
    `code` varchar(20) DEFAULT NULL,
    `name` varchar(50) DEFAULT NULL,
    `version` varchar(20) DEFAULT NULL,
    `price` decimal(10,2) DEFAULT NULL,
    `operation_type` enum('INSERT','UPDATE','DELETE') NOT NULL,
    `operation_time` datetime DEFAULT current_timestamp()
    ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

    DELIMITER $$
    CREATE TRIGGER ds_os_after_insert
    AFTER INSERT ON ds_os
    FOR EACH ROW
    BEGIN
    INSERT INTO ds_os_backup_incremental
    SELECT *, 'INSERT', NOW() FROM ds_os WHERE id = NEW.id;
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE TRIGGER ds_os_after_update
    AFTER UPDATE ON ds_os
    FOR EACH ROW
    BEGIN
    INSERT INTO ds_os_backup_incremental
    SELECT *, 'UPDATE', NOW() FROM ds_os WHERE id = NEW.id;
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE TRIGGER ds_os_after_delete
    AFTER DELETE ON ds_os
    FOR EACH ROW
    BEGIN
    INSERT INTO ds_os_backup_incremental
    VALUES (
    OLD.id, OLD.code, OLD.name, OLD.version, OLD.price,
    'DELETE', NOW()
    );
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE PROCEDURE export_to_file_ds_os()
    BEGIN
    -- Construir la ruta del archivo dinámicamente
    SET @file_path = CONCAT('C:/mariadbbdss/segu/ds_os_incremental_', DATE_FORMAT(NOW(), 
    '%Y%m%d%H%i%s'), '.csv');
    -- Construir el comando SQL dinámico
    SET @sql = CONCAT(
    'SELECT * FROM ds_os_backup_incremental ',
    'INTO OUTFILE "', @file_path, '" ',
    'FIELDS TERMINATED BY "," ',
    'ENCLOSED BY \""\" ',
    'LINES TERMINATED BY "\\r\\n"'
    );
    -- Ejecutar el SQL dinámico
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    -- Limpiar la tabla incremental
    DELETE FROM ds_os_backup_incremental;
    END$$
    DELIMITER ;

-- Private bandwidth table backup

    CREATE TABLE `ds_private_bandwidth_backup_incremental` (
    `id` int(11) DEFAULT 0,
    `code` varchar(20) DEFAULT NULL,
    `description` tinytext DEFAULT NULL,
    `price` decimal(10,2) DEFAULT NULL,
    `operation_type` enum('INSERT','UPDATE','DELETE') NOT NULL,
    `operation_time` datetime DEFAULT current_timestamp()
    ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

    DELIMITER $$
    CREATE TRIGGER ds_private_bandwidth_after_insert
    AFTER INSERT ON ds_private_bandwidth
    FOR EACH ROW
    BEGIN
    INSERT INTO ds_private_bandwidth_backup_incremental
    SELECT *, 'INSERT', NOW() FROM ds_private_bandwidth WHERE id = NEW.id;
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE TRIGGER ds_private_bandwidth_after_update
    AFTER UPDATE ON ds_private_bandwidth
    FOR EACH ROW
    BEGIN
    INSERT INTO ds_private_bandwidth_backup_incremental
    SELECT *, 'UPDATE', NOW() FROM ds_private_bandwidth WHERE id = NEW.id;
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE TRIGGER ds_private_bandwidth_after_delete
    AFTER DELETE ON ds_private_bandwidth
    FOR EACH ROW
    BEGIN
    INSERT INTO ds_private_bandwidth_backup_incremental
    VALUES (
    OLD.id, OLD.code, OLD.description, OLD.price,
    'DELETE', NOW()
    );
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE PROCEDURE export_to_file_ds_private_bandwidth()
    BEGIN
    -- Construir la ruta del archivo dinámicamente
    SET @file_path = CONCAT('C:/mariadbbdss/segu/ds_private_bandwidth_incremental_', 
    DATE_FORMAT(NOW(), '%Y%m%d%H%i%s'), '.csv');
    -- Construir el comando SQL dinámico
    SET @sql = CONCAT(
    'SELECT * FROM ds_private_bandwidth_backup_incremental ',
    'INTO OUTFILE "', @file_path, '" ',
    'FIELDS TERMINATED BY "," ',
    'ENCLOSED BY \""\" ',
    'LINES TERMINATED BY "\\r\\n"'
    );
    -- Ejecutar el SQL dinámico
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    -- Limpiar la tabla incremental
    DELETE FROM ds_private_bandwidth_backup_incremental;
    END$$
    DELIMITER ;

-- Public bandwidth table backup

    CREATE TABLE `ds_public_bandwidth_backup_incremental` (
    `id` int(11) DEFAULT 0,
    `code` varchar(20) DEFAULT NULL,
    `description` tinytext DEFAULT NULL,
    `price` decimal(10,2) DEFAULT NULL,
    `operation_type` enum('INSERT','UPDATE','DELETE') NOT NULL,
    `operation_time` datetime DEFAULT current_timestamp()
    ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

    DELIMITER $$
    CREATE TRIGGER ds_public_bandwidth_after_insert
    AFTER INSERT ON ds_public_bandwidth
    FOR EACH ROW
    BEGIN
    INSERT INTO ds_public_bandwidth_backup_incremental
    SELECT *, 'INSERT', NOW() FROM ds_public_bandwidth WHERE id = NEW.id;
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE TRIGGER ds_public_bandwidth_after_update
    AFTER UPDATE ON ds_public_bandwidth
    FOR EACH ROW
    BEGIN
    INSERT INTO ds_public_bandwidth_backup_incremental
    SELECT *, 'UPDATE', NOW() FROM ds_public_bandwidth WHERE id = NEW.id;
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE TRIGGER ds_public_bandwidth_after_delete
    AFTER DELETE ON ds_public_bandwidth
    FOR EACH ROW
    BEGIN
    INSERT INTO ds_public_bandwidth_backup_incremental
    VALUES (
    OLD.id, OLD.code, OLD.description, OLD.price,
    'DELETE', NOW()
    );
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE PROCEDURE export_to_file_ds_public_bandwidth()
    BEGIN
    -- Construir la ruta del archivo dinámicamente
    SET @file_path = CONCAT('C:/mariadbbdss/segu/ds_public_bandwidth_incremental_', 
    DATE_FORMAT(NOW(), '%Y%m%d%H%i%s'), '.csv');
    -- Construir el comando SQL dinámico
    SET @sql = CONCAT(
    'SELECT * FROM ds_public_bandwidth_backup_incremental ',
    'INTO OUTFILE "', @file_path, '" ',
    'FIELDS TERMINATED BY "," ',
    'ENCLOSED BY \""\" ',
    'LINES TERMINATED BY "\\r\\n"'
    );
    -- Ejecutar el SQL dinámico
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    -- Limpiar la tabla incremental
    DELETE FROM ds_public_bandwidth_backup_incremental;
    END$$
    DELIMITER ;

-- Processor table backup

    CREATE TABLE `ds_processor_backup_incremental` (
    `id` int(11) DEFAULT 0,
    `code` varchar(20) DEFAULT NULL,
    `description` tinytext DEFAULT NULL,
    `cores` int(11) DEFAULT NULL,
    `speed_ghz` decimal(5,2) DEFAULT NULL,
    `price` decimal(10,2) DEFAULT NULL,
    `operation_type` enum('INSERT','UPDATE','DELETE') NOT NULL,
    `operation_time` datetime DEFAULT current_timestamp()
    ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

    DELIMITER $$
    CREATE TRIGGER ds_processor_after_insert
    AFTER INSERT ON ds_processor
    FOR EACH ROW
    BEGIN
    INSERT INTO ds_processor_backup_incremental
    SELECT *, 'INSERT', NOW() FROM ds_processor WHERE id = NEW.id;
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE TRIGGER ds_processor_after_update
    AFTER UPDATE ON ds_processor
    FOR EACH ROW
    BEGIN
    INSERT INTO ds_processor_backup_incremental
    SELECT *, 'UPDATE', NOW() FROM ds_processor WHERE id = NEW.id;
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE TRIGGER ds_processor_after_delete
    AFTER DELETE ON ds_processor
    FOR EACH ROW
    BEGIN
    INSERT INTO ds_processor_backup_incremental
    VALUES (
    OLD.id, OLD.code, OLD.description, OLD.cores, OLD.speed_ghz, OLD.price,
    'DELETE', NOW()
    );
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE PROCEDURE export_to_file_ds_processor()
    BEGIN
    -- Construir la ruta del archivo dinámicamente
    SET @file_path = CONCAT('C:/mariadbbdss/segu/ds_processor_incremental_', DATE_FORMAT(NOW(), 
    '%Y%m%d%H%i%s'), '.csv');
    -- Construir el comando SQL dinámico
    SET @sql = CONCAT(
    'SELECT * FROM ds_processor_backup_incremental ',
    'INTO OUTFILE "', @file_path, '" ',
    'FIELDS TERMINATED BY "," ',
    'ENCLOSED BY \""\" ',
    'LINES TERMINATED BY "\\r\\n"'
    );
    -- Ejecutar el SQL dinámico
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    -- Limpiar la tabla incremental
    DELETE FROM ds_processor_backup_incremental;
    END$$
    DELIMITER ;

-- Storage table backup

    CREATE TABLE `ds_storage_backup_incremental` (
    `id` int(11) DEFAULT 0,
    `code` varchar(20) DEFAULT NULL,
    `description` tinytext DEFAULT NULL,
    `capacity_gb` int(11) DEFAULT NULL,
    `price` decimal(10,2) DEFAULT NULL,
    `operation_type` enum('INSERT','UPDATE','DELETE') NOT NULL,
    `operation_time` datetime DEFAULT current_timestamp()
    ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

    DELIMITER $$
    CREATE TRIGGER ds_storage_after_insert
    AFTER INSERT ON ds_storage
    FOR EACH ROW
    BEGIN
    INSERT INTO ds_storage_backup_incremental
    SELECT *, 'INSERT', NOW() FROM ds_storage WHERE id = NEW.id;
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE TRIGGER ds_storage_after_update
    AFTER UPDATE ON ds_storage
    FOR EACH ROW
    BEGIN
    INSERT INTO ds_storage_backup_incremental
    SELECT *, 'UPDATE', NOW() FROM ds_storage WHERE id = NEW.id;
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE TRIGGER ds_storage_after_delete
    AFTER DELETE ON ds_storage
    FOR EACH ROW
    BEGIN
    INSERT INTO ds_storage_backup_incremental
    VALUES (
    OLD.id, OLD.code, OLD.description, OLD.capacity_gb, OLD.price,
    'DELETE', NOW()
    );
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE PROCEDURE export_to_file_ds_storage()
    BEGIN
    -- Construir la ruta del archivo dinámicamente
    SET @file_path = CONCAT('C:/mariadbbdss/segu/ds_storage_incremental_', DATE_FORMAT(NOW(), 
    '%Y%m%d%H%i%s'), '.csv');
    -- Construir el comando SQL dinámico
    SET @sql = CONCAT(
    'SELECT * FROM ds_storage_backup_incremental ',
    'INTO OUTFILE "', @file_path, '" ',
    'FIELDS TERMINATED BY "," ',
    'ENCLOSED BY \""\" ',
    'LINES TERMINATED BY "\\r\\n"'
    );
    -- Ejecutar el SQL dinámico
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    -- Limpiar la tabla incremental
    DELETE FROM ds_storage_backup_incremental;
    END$$
    DELIMITER ;

-- PAAS Dedicated Server table backup

    CREATE TABLE `paas_dedicated_server_backup_incremental` (
    `id` int(11) DEFAULT 0,
    `price` decimal(10,2) DEFAULT NULL,
    `category_code` varchar(20) DEFAULT NULL,
    `public_bandwidth_code` varchar(20) DEFAULT NULL,
    `private_bandwidth_code` varchar(20) DEFAULT NULL,
    `storage_code` varchar(20) DEFAULT NULL,
    `memory_code` varchar(20) DEFAULT NULL,
    `processor_code` varchar(20) DEFAULT NULL,
    `data_center_region_code` varchar(20) DEFAULT NULL,
    `os_code` varchar(20) DEFAULT NULL,
    `commitment_period` varchar(20) DEFAULT NULL,
    `request_id` int(11) DEFAULT NULL,
    `commitment_id` int(11) DEFAULT NULL,
    `operation_type` enum('INSERT','UPDATE','DELETE') NOT NULL,
    `operation_time` datetime DEFAULT current_timestamp()
    
    ) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

    DELIMITER $$
    CREATE TRIGGER paas_dedicated_server_after_insert
    AFTER INSERT ON paas_dedicated_server
    FOR EACH ROW
    BEGIN
    INSERT INTO paas_dedicated_server_backup_incremental
    SELECT *, 'INSERT', NOW() FROM paas_dedicated_server WHERE id = NEW.id;
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE TRIGGER paas_dedicated_server_after_update
    AFTER UPDATE ON paas_dedicated_server
    FOR EACH ROW
    BEGIN
    INSERT INTO paas_dedicated_server_backup_incremental
    SELECT *, 'UPDATE', NOW() FROM paas_dedicated_server WHERE id = NEW.id;
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE TRIGGER paas_dedicated_server_after_delete
    AFTER DELETE ON paas_dedicated_server
    FOR EACH ROW
    BEGIN
    INSERT INTO paas_dedicated_server_backup_incremental
    VALUES (
    OLD.id, OLD.price, OLD.category_code, OLD.public_bandwidth_code, OLD.private_bandwidth_code,
    OLD.storage_code, OLD.memory_code, OLD.processor_code, OLD.data_center_region_code,
    OLD.os_code, OLD.commitment_period, OLD.request_id, OLD.commitment_id,
    'DELETE', NOW()
    );
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE TRIGGER request_before_delete
    BEFORE DELETE ON request
    FOR EACH ROW
    BEGIN
    -- Copia los registros relacionados de paas_dedicated_server a la tabla de respaldo antes de que sean eliminados
    INSERT INTO paas_dedicated_server_backup_incremental
    SELECT 
    id, price, category_code, public_bandwidth_code, private_bandwidth_code,
    storage_code, memory_code, processor_code, data_center_region_code,
    os_code, commitment_period, request_id, commitment_id,
    'DELETE', NOW()
    FROM paas_dedicated_server
    WHERE request_id = OLD.request_id;
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE PROCEDURE export_to_file_paas_dedicated_server()
    BEGIN
    -- Construir la ruta del archivo dinámicamente
    SET @file_path = CONCAT('C:/mariadbbdss/segu/paas_dedicated_server_incremental_', 
    DATE_FORMAT(NOW(), '%Y%m%d%H%i%s'), '.csv');
    -- Construir el comando SQL dinámico
    SET @sql = CONCAT(
    'SELECT * FROM paas_dedicated_server_backup_incremental ',
    'INTO OUTFILE "', @file_path, '" ',
    'FIELDS TERMINATED BY "," ',
    'ENCLOSED BY \""\" ',
    'LINES TERMINATED BY "\\r\\n"'
    );
    -- Ejecutar el SQL dinámico
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    -- Limpiar la tabla incremental
    DELETE FROM paas_dedicated_server_backup_incremental;
    END$$
    DELIMITER ;

-- Request table backup

    CREATE TABLE `request_backup_incremental` (
    `request_id` int(11) DEFAULT 0,
    `date` datetime DEFAULT NULL,
    `user_id` int(11) DEFAULT NULL,
    `state` tinyint(1) DEFAULT 0,
    `operation_type` enum('INSERT','UPDATE','DELETE') NOT NULL,
    `operation_time` datetime DEFAULT current_timestamp()
    );

    DELIMITER $$
    CREATE TRIGGER request_after_insert
    AFTER INSERT ON request
    FOR EACH ROW
    BEGIN
    INSERT INTO request_backup_incremental
    SELECT *, 'INSERT', NOW() FROM request WHERE request_id = NEW. request_id;
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE TRIGGER request_after_update
    AFTER UPDATE ON request
    FOR EACH ROW
    BEGIN
    INSERT INTO request_backup_incremental
    SELECT *, 'UPDATE', NOW() FROM request WHERE request_id = NEW. request_id;
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE TRIGGER request_after_delete
    AFTER DELETE ON request
    FOR EACH ROW
    BEGIN
    INSERT INTO request_backup_incremental
    VALUES (
    OLD.request_id, OLD.date, OLD.user_id, OLD.state,
    'DELETE', NOW()
    );
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE PROCEDURE export_to_file_request()
    BEGIN
    -- Construir la ruta del archivo dinámicamente
    SET @file_path = CONCAT('C:/mariadbbdss/segu/request_incremental_', DATE_FORMAT(NOW(), 
    '%Y%m%d%H%i%s'), '.csv');
    -- Construir el comando SQL dinámico
    SET @sql = CONCAT(
    'SELECT * FROM request_backup_incremental ',
    'INTO OUTFILE "', @file_path, '" ',
    'FIELDS TERMINATED BY "," ',
    'ENCLOSED BY \""\" ',
    'LINES TERMINATED BY "\\r\\n"'
    );
    -- Ejecutar el SQL dinámico
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    -- Limpiar la tabla incremental
    DELETE FROM request_backup_incremental;
    END$$
    DELIMITER ;

-- Password History table backup

    CREATE TABLE `u_password_history_backup_incremental` (
    `id` int(11) DEFAULT 0,
    `user_id` int(11) DEFAULT NULL,
    `password` varchar(256) DEFAULT NULL,
    `change_date` datetime DEFAULT NULL,
    `operation_type` enum('INSERT','UPDATE','DELETE') NOT NULL,
    `operation_time` datetime DEFAULT current_timestamp()
    ) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

    DELIMITER $$
    CREATE TRIGGER u_password_history_after_insert
    AFTER INSERT ON u_password_history
    FOR EACH ROW
    BEGIN
    INSERT INTO u_password_history_backup_incremental
    SELECT *, 'INSERT', NOW() FROM u_password_history WHERE id = NEW.id;
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE TRIGGER u_password_history_after_update
    AFTER UPDATE ON u_password_history
    FOR EACH ROW
    BEGIN
    INSERT INTO u_password_history_backup_incremental
    SELECT *, 'UPDATE', NOW() FROM u_password_history WHERE id = NEW.id;
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE TRIGGER u_password_history_after_delete
    AFTER DELETE ON u_password_history
    FOR EACH ROW
    BEGIN
    INSERT INTO u_password_history_backup_incremental
    VALUES (
    OLD.id, OLD.user_id, OLD.password, OLD.change_date,
    'DELETE', NOW()
    );
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE PROCEDURE export_to_file_u_password_history()
    BEGIN
    -- Construir la ruta del archivo dinámicamente
    SET @file_path = CONCAT('C:/mariadbbdss/segu/u_password_history_incremental_', 
    DATE_FORMAT(NOW(), '%Y%m%d%H%i%s'), '.csv');
    -- Construir el comando SQL dinámico
    SET @sql = CONCAT(
    'SELECT * FROM u_password_history_backup_incremental ',
    'INTO OUTFILE "', @file_path, '" ',
    'FIELDS TERMINATED BY "," ',
    'ENCLOSED BY \""\" ',
    'LINES TERMINATED BY "\\r\\n"'
    );
    -- Ejecutar el SQL dinámico
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    -- Limpiar la tabla incremental
    DELETE FROM u_password_history_backup_incremental;
    END$$
    DELIMITER ;

-- Role table backup

    CREATE TABLE `u_role_backup_incremental` (
    `id` int(11) DEFAULT 0,
    `role_name` varchar(50) DEFAULT NULL,
    `description` varchar(256) DEFAULT NULL,
    `code` varchar(20) DEFAULT NULL,
    `operation_type` enum('INSERT','UPDATE','DELETE') NOT NULL,
    `operation_time` datetime DEFAULT current_timestamp()
    );

    DELIMITER $$
    CREATE TRIGGER u_role_after_insert
    AFTER INSERT ON u_role
    FOR EACH ROW
    BEGIN
    INSERT INTO u_role_backup_incremental
    SELECT *, 'INSERT', NOW() FROM u_role WHERE id = NEW.id;
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE TRIGGER u_role_after_update
    AFTER UPDATE ON u_role
    FOR EACH ROW
    BEGIN
    INSERT INTO u_role_backup_incremental
    SELECT *, 'UPDATE', NOW() FROM u_role WHERE id = NEW.id;
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE TRIGGER u_role_after_delete
    AFTER DELETE ON u_role
    FOR EACH ROW
    BEGIN
    INSERT INTO u_role_backup_incremental
    VALUES (
    OLD.id, OLD.role_name, OLD.description, OLD.code,
    'DELETE', NOW()
    );
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE TRIGGER u_role_before_delete
    BEFORE DELETE ON u_role
    FOR EACH ROW
    BEGIN
    -- Copiar registros relacionados de u_user_x_role antes de la eliminación
    INSERT INTO u_user_x_role_backup_incremental (id, user_id, role_id, operation_type, operation_time)
    SELECT 
    id, user_id, role_id, 'DELETE', NOW()
    FROM u_user_x_role
    WHERE role_id = OLD.code;
    -- Copiar el registro eliminado de u_role
    INSERT INTO u_role_backup_incremental (id, role_name, description, code, operation_type, 
    operation_time)
    SELECT 
    id, role_name, description, code, 'DELETE', NOW()
    FROM u_role
    WHERE code = OLD.code;
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE PROCEDURE export_to_file_u_role()
    BEGIN
    -- Construir la ruta del archivo dinámicamente
    SET @file_path = CONCAT('C:/mariadbbdss/segu/u_role_incremental_', DATE_FORMAT(NOW(), 
    '%Y%m%d%H%i%s'), '.csv');
    -- Construir el comando SQL dinámico
    SET @sql = CONCAT(
    'SELECT * FROM u_role_backup_incremental ',
    'INTO OUTFILE "', @file_path, '" ',
    'FIELDS TERMINATED BY "," ',
    'ENCLOSED BY \""\" ',
    'LINES TERMINATED BY "\\r\\n"'
    );
    -- Ejecutar el SQL dinámico
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    -- Limpiar la tabla incremental
    DELETE FROM u_role_backup_incremental;
    END$$
    DELIMITER ;

-- User x Role table backup

    CREATE TABLE `u_user_x_role_backup_incremental` (
    `id` int(11) DEFAULT 0,
    `user_id` int(11) DEFAULT NULL,
    `role_id` varchar(24) DEFAULT NULL ,
    `operation_type` enum('INSERT','UPDATE','DELETE') NOT NULL,
    `operation_time` datetime DEFAULT current_timestamp()
    ) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

    DELIMITER $$
    CREATE TRIGGER u_user_x_role_after_insert
    AFTER INSERT ON u_user_x_role
    FOR EACH ROW
    BEGIN
    INSERT INTO u_user_x_role_backup_incremental
    SELECT *, 'INSERT', NOW() FROM u_user_x_role WHERE id = NEW.id;
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE TRIGGER u_user_x_role_after_update
    AFTER UPDATE ON u_user_x_role
    FOR EACH ROW
    BEGIN
    INSERT INTO u_user_x_role_backup_incremental
    SELECT *, 'UPDATE', NOW() FROM u_user_x_role WHERE id = NEW.id;
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE TRIGGER u_user_x_role_after_delete
    AFTER DELETE ON u_user_x_role
    FOR EACH ROW
    BEGIN
    INSERT INTO u_user_x_role_backup_incremental
    VALUES (
    OLD.id, OLD.user_id, OLD.role_id,
    'DELETE', NOW()
    );
    END$$
    DELIMITER ;

    DELIMITER $$
    CREATE PROCEDURE export_to_file_u_user_x_role()
    BEGIN
    -- Construir la ruta del archivo dinámicamente
    SET @file_path = CONCAT('C:/mariadbbdss/segu/u_user_x_role_incremental_', DATE_FORMAT(NOW(), 
    '%Y%m%d%H%i%s'), '.csv');
    -- Construir el comando SQL dinámico
    SET @sql = CONCAT(
    'SELECT * FROM u_user_x_role_backup_incremental ',
    'INTO OUTFILE "', @file_path, '" ',
    'FIELDS TERMINATED BY "," ',
    'ENCLOSED BY \""\" ',
    'LINES TERMINATED BY "\\r\\n"'
    );
    -- Ejecutar el SQL dinámico
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    -- Limpiar la tabla incremental
    DELETE FROM u_user_x_role_backup_incremental;
    END$$
    DELIMITER ;

-- ////////////////////
-- Daily incremental backup

DELIMITER $$
CREATE EVENT daily_incremental_backup
ON SCHEDULE EVERY 1 DAY
STARTS CURRENT_TIMESTAMP
DO
BEGIN
    CALL export_to_file_user();
    CALL export_to_file_category_backup_incremental();
    CALL export_to_file_commitment_period_backup_incremental();
    CALL export_to_file_ds_datacenterregion_backup_incremental();
    CALL export_to_file_ds_memory_backup_incremental();
    CALL export_to_file_ds_os_backup_incremental();
    CALL export_to_file_ds_private_bandwidth_backup_incremental();
    CALL export_to_file_ds_public_bandwidth_backup_incremental();
    CALL export_to_file_ds_processor_backup_incremental();
    CALL export_to_file_ds_storage_backup_incremental();
    CALL export_to_file_paas_dedicated_server_backup_incremental();
    CALL export_to_file_request_backup_incremental();
    CALL export_to_file_u_password_history_backup_incremental();
    CALL export_to_file_u_role_backup_incremental();
    CALL export_to_file_u_user_x_role_backup_incremental();
END$$
DELIMITER ;