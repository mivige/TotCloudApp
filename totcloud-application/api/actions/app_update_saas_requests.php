<?php
    // Enable detailed error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Include necessary files and start session
    include_once '../https_redirect.php';
    session_start();
    include_once '../config/database_app.php';
    date_default_timezone_set('UTC');
    include_once "../config/variables.php";

    // Get and clean input values
    $modificar = isset($_POST['modificar']) ? trim($_POST['modificar']) : '';
    $id = isset($_POST['id']) ? trim($_POST['id']) : '';

    // Validate ID if modification is requested
    if (empty($id) && $modificar == 1) {
        echo "ERROR DE ID";
        exit;
    } else {
        // Retrieve and clean form inputs
        $category = isset($_POST['category']) ? StringInputCleaner(trim($_POST['category'])) : 0;
        $datacenter = isset($_POST['datacenter']) ? StringInputCleaner(trim($_POST['datacenter'])) : 0;
        $ssl = isset($_POST['ssl']) ? StringInputCleaner(trim($_POST['ssl'])) : null;
        $storageSpace = isset($_POST['storageSpace']) ? StringInputCleaner(trim($_POST['storageSpace'])) : 1;
        $bandwidthAllocation = isset($_POST['bandwidthAllocation']) ? StringInputCleaner(trim($_POST['bandwidthAllocation'])) : 1;
        $maxConcurrentUsers = isset($_POST['maxConcurrentUsers']) ? StringInputCleaner(trim($_POST['maxConcurrentUsers'])) : 1;
        $maxWebsites = isset($_POST['maxWebsites']) ? StringInputCleaner(trim($_POST['maxWebsites'])) : 1;
        $isDomainIncluded = isset($_POST['isDomainIncluded']) ? 1 : 0;
        $domain = isset($_POST['domain']) ? StringInputCleaner(trim($_POST['domain'])) : null;
        $isEmailIncluded = isset($_POST['isEmailIncluded']) ? 1 : 0;
        $database = isset($_POST['database']) ? StringInputCleaner(trim($_POST['database'])) : null;
        $commitment = isset($_POST['commitment']) ? StringInputCleaner(trim($_POST['commitment'])) : 0;
        $id_user = isset($_POST['id_user']) ? StringInputCleaner(trim($_POST['id_user'])) : 0;
    }

    // Check if user token is set and valid
    if (!isset($_SESSION['app_user_token']) || empty($_SESSION['app_user_token'])) {
        echo "TOKEN ERROR";
        exit;
    }

    // Initialize the default response
    $resultado = "ERROR";

    try {
        // Start a database transaction
        $dbb->begin_transaction();
        
        if ($modificar == 1) {
            // Update existing request
            // First, update the request record (if needed)
            $stmt = $dbb->prepare('UPDATE request SET user_id = ? WHERE request_id = (SELECT request_id FROM saas_web_hosting WHERE id = ?)');
            $stmt->bind_param('si', $id_user, $id);
            $stmt->execute();

            // Update saas_web_hosting record
            $stmt = $dbb->prepare('UPDATE saas_web_hosting 
                SET category_code = ?, storageSpace = ?, bandwidthAllocation = ?, 
                    maxConcurrentUsers = ?, maxWebsites = ?, isDomainIncluded = ?, 
                    isEmailIncluded = ?, datacenter_id = ?, ssl_id = ?, 
                    commitment_id = ?
                WHERE id = ?');
            
            $stmt->bind_param('siiiiiiiiii', 
                $category, 
                $storageSpace, 
                $bandwidthAllocation, 
                $maxConcurrentUsers, 
                $maxWebsites, 
                $isDomainIncluded, 
                $isEmailIncluded, 
                $datacenter, 
                $ssl,  
                $commitment, 
                $id
            );

            if (!$stmt->execute()) {
                throw new Exception("Failed to update saas_web_hosting");
            }

        } else {
            // Insert new request
            $fecha_request = date("Y-m-d H:i:s");
            
            // Insert into request table
            $stmt = $dbb->prepare('INSERT INTO request (date, user_id) VALUES (?, ?)');
            $stmt->bind_param('ss', $fecha_request, $id_user);
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to insert request");
            }
            
            // Get the last inserted request_id
            $request_id = $dbb->insert_id;

            // Insert into saas_web_hosting table
            $stmt = $dbb->prepare('INSERT INTO saas_web_hosting 
                (name, category_code, storageSpace, bandwidthAllocation, maxConcurrentUsers, 
                maxWebsites, isDomainIncluded, isEmailIncluded, 
                datacenter_id, ssl_id, request_id, commitment_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
            
            $stmt->bind_param('ssiiiiiiiiii', 
                $category,
                $category, 
                $storageSpace, 
                $bandwidthAllocation, 
                $maxConcurrentUsers, 
                $maxWebsites, 
                $isDomainIncluded, 
                $isEmailIncluded, 
                $datacenter, 
                $ssl, 
                $request_id, 
                $commitment
            );

            if (!$stmt->execute()) {
                throw new Exception("Failed to insert saas_web_hosting");
            }
        }

        // Commit the transaction
        $dbb->commit();

        // Redirect on success
        header("Location: ../../index.php?opcion=saas_requests&error=2");
        exit;

    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $dbb->rollback();

        // Log the error (you might want to implement proper error logging)
        error_log("SaaS Web Hosting Request Error: " . $e->getMessage());
        error_log("Error Details: " . print_r($e, true));
        error_log("Full SQL Error: " . $dbb->error);

        // Redirect with error
        header("Location: ../../index.php?opcion=saas_requests&error=3");
        exit;
    }

    // Output the result (though this should never be reached due to exit statements)
    echo $resultado;
?>