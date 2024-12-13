DELIMITER $$

-- Trigger for category table modifications
CREATE TRIGGER log_category_changes AFTER UPDATE ON category FOR EACH ROW BEGIN
    INSERT INTO logs (timestamp, eventType, details)
    VALUES (NOW(), 7, CONCAT('Category modification: ', OLD.name, ' updated'));
END$$

-- Trigger for commitment_period table modifications
CREATE TRIGGER log_commitment_period_changes AFTER UPDATE ON commitment_period FOR EACH ROW BEGIN
    INSERT INTO logs (timestamp, eventType, details)
    VALUES (NOW(), 7, CONCAT('Commitment Period modification: ', OLD.code, ' updated'));
END$$

-- Trigger for datacenterregion table modifications
CREATE TRIGGER log_datacenterregion_changes AFTER UPDATE ON ds_datacenterregion FOR EACH ROW BEGIN
    INSERT INTO logs (timestamp, eventType, details)
    VALUES (NOW(), 7, CONCAT('Datacenter Region modification: ', OLD.region_name, ' updated'));
END$$

-- Trigger for ds_memory table modifications
CREATE TRIGGER log_ds_memory_changes AFTER UPDATE ON ds_memory FOR EACH ROW BEGIN
    INSERT INTO logs (timestamp, eventType, details)
    VALUES (NOW(), 7, CONCAT('Memory Configuration modification: ', OLD.code, ' updated'));
END$$

-- Trigger for ds_os table modifications
CREATE TRIGGER log_ds_os_changes AFTER UPDATE ON ds_os FOR EACH ROW BEGIN
    INSERT INTO logs (timestamp, eventType, details)
    VALUES (NOW(), 7, CONCAT('OS Configuration modification: ', OLD.name, ' (', OLD.version, ') updated'));
END$$

-- Trigger for ds_private_bandwidth table modifications
CREATE TRIGGER log_private_bandwidth_changes AFTER UPDATE ON ds_private_bandwidth FOR EACH ROW BEGIN
    INSERT INTO logs (timestamp, eventType, details)
    VALUES (NOW(), 7, CONCAT('Private Bandwidth Configuration modification: ', OLD.code, ' updated'));
END$$

-- Trigger for ds_public_bandwidth table modifications
CREATE TRIGGER log_public_bandwidth_changes AFTER UPDATE ON ds_public_bandwidth FOR EACH ROW BEGIN
    INSERT INTO logs (timestamp, eventType, details)
    VALUES (NOW(), 7, CONCAT('Public Bandwidth Configuration modification: ', OLD.code, ' updated'));
END$$

-- Trigger for ds_processor table modifications
CREATE TRIGGER log_processor_changes AFTER UPDATE ON ds_processor FOR EACH ROW BEGIN
    INSERT INTO logs (timestamp, eventType, details)
    VALUES (NOW(), 7, CONCAT('Processor Configuration modification: ', OLD.code, ' updated'));
END$$

-- Trigger for ds_storage table modifications
CREATE TRIGGER log_storage_changes AFTER UPDATE ON ds_storage FOR EACH ROW BEGIN
    INSERT INTO logs (timestamp, eventType, details)
    VALUES (NOW(), 7, CONCAT('Storage Configuration modification: ', OLD.code, ' updated')
    );
END$$

DELIMITER ;

DELIMITER $$

-- Triggers for wh_datacenter table
CREATE TRIGGER log_datacenter_changes AFTER UPDATE ON wh_datacenter FOR EACH ROW BEGIN
    INSERT INTO logs (timestamp, eventType, details)
    VALUES (NOW(), 7, CONCAT('Datacenter modification: ', OLD.name, ' at ', OLD.location, ' updated'));
END$$

-- Triggers for wh_ssl table
CREATE TRIGGER log_ssl_changes AFTER UPDATE ON wh_ssl FOR EACH ROW BEGIN
    INSERT INTO logs (timestamp, eventType, details)
    VALUES (NOW(), 7, CONCAT('SSL Certificate modification: ', OLD.provider, ' ', OLD.certificateType, ' updated'));
END$$

-- Triggers for wh_db_dbms table
CREATE TRIGGER log_db_dbms_changes AFTER UPDATE ON wh_db_dbms FOR EACH ROW BEGIN
    INSERT INTO logs (timestamp, eventType, details)
    VALUES (NOW(), 7, CONCAT('DBMS modification: ', OLD.name, ' version ', OLD.version, ' updated'));
END$$

-- Triggers for wh_db_memory table
CREATE TRIGGER log_db_memory_changes AFTER UPDATE ON wh_db_memory FOR EACH ROW BEGIN
    INSERT INTO logs (timestamp, eventType, details)
    VALUES (NOW(), 7, CONCAT('Database Memory Configuration modification: ', OLD.capacity, 'GB ', OLD.type, ' updated'));
END$$

-- Triggers for wh_db_persistency table
CREATE TRIGGER log_db_persistency_changes AFTER UPDATE ON wh_db_persistency FOR EACH ROW BEGIN
    INSERT INTO logs (timestamp, eventType, details)
    VALUES (NOW(), 7, CONCAT('Database Persistency Type modification: ', OLD.type, ' updated'));
END$$

-- Triggers for wh_dns table
CREATE TRIGGER log_dns_changes AFTER UPDATE ON wh_dns FOR EACH ROW BEGIN
    INSERT INTO logs (timestamp, eventType, details)
    VALUES (NOW(), 7, CONCAT('DNS Record modification: ', OLD.domainName, ' ', OLD.recordType, ' record updated'));
END$$

-- Triggers for wh_domain table
CREATE TRIGGER log_domain_changes AFTER UPDATE ON wh_domain FOR EACH ROW BEGIN
    INSERT INTO logs (timestamp, eventType, details)
    VALUES (NOW(), 7, CONCAT('Domain modification: ', OLD.name, ' from ', OLD.registrar, ' updated'));
END$$

-- Triggers for wh_modules table
CREATE TRIGGER log_modules_changes AFTER UPDATE ON wh_modules FOR EACH ROW BEGIN
    INSERT INTO logs (timestamp, eventType, details)
    VALUES (NOW(), 7, CONCAT('Web Hosting Module modification: ', OLD.name, ' version ', OLD.version, ' updated'));
END$$

-- Triggers for wh_cdn table
CREATE TRIGGER log_cdn_changes AFTER UPDATE ON wh_cdn FOR EACH ROW BEGIN
    INSERT INTO logs (timestamp, eventType, details)
    VALUES (NOW(), 7, CONCAT('CDN modification: ', OLD.name, ' with endpoint ', OLD.endpoint, ' updated'));
END$$

-- Triggers for wh_cdn_geolocation table
CREATE TRIGGER log_cdn_geolocation_changes AFTER UPDATE ON wh_cdn_geolocation FOR EACH ROW BEGIN
    INSERT INTO logs (timestamp, eventType, details)
    VALUES (NOW(), 7, CONCAT('CDN Geolocation modification: ', OLD.region, ' with ', OLD.latency, 'ms latency updated'));
END$$

DELIMITER ;