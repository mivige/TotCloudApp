DELIMITER $$

CREATE PROCEDURE ManagePartitions()
BEGIN
    DECLARE last_partition_name VARCHAR(255);
    DECLARE max_partition_value INT;

    -- Identify oldest partition 
    SELECT PARTITION_NAME
    INTO last_partition_name
    FROM INFORMATION_SCHEMA.PARTITIONS
    WHERE TABLE_SCHEMA = 'totcloud_db'
      AND TABLE_NAME = 'logs'
    ORDER BY PARTITION_ORDINAL_POSITION ASC
    LIMIT 1;

    -- Delete oldest partition
    IF last_partition_name IS NOT NULL THEN
        SET @sql = CONCAT('ALTER TABLE logs DROP PARTITION ', last_partition_name);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;

    -- Calculate value for new partition (next month)
    SELECT MAX(PARTITION_DESCRIPTION)
    INTO max_partition_value
    FROM INFORMATION_SCHEMA.PARTITIONS
    WHERE TABLE_SCHEMA = 'totcloud_db'
      AND TABLE_NAME = 'logs';

    IF max_partition_value IS NOT NULL THEN
        -- Add new partition for the next month
        SET @new_partition_value = max_partition_value + 1;
        SET @new_partition_name = CONCAT('p', @new_partition_value);
        SET @sql = CONCAT('ALTER TABLE logs ADD PARTITION (PARTITION ', 
                          @new_partition_name, ' VALUES LESS THAN (', @new_partition_value + 1, '))');
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END $$

DELIMITER ;
