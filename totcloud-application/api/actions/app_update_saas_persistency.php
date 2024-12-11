<?php
include_once '../https_redirect.php';
session_start();
include_once '../config/database_app.php';
date_default_timezone_set('UTC');
include_once "../config/variables.php";

$modify = isset($_POST['modificar']) ? trim($_POST['modificar']) : '';
$id = isset($_POST['id']) ? trim($_POST['id']) : '';

// Validate ID when modifying
if (empty($id) && $modify == 1) {
    echo "ERROR: Missing ID";
    exit;
} else {
    // Get form data
    $type = isset($_POST['type']) ? trim($_POST['type']) : '';
    $type = StringInputCleaner($type);
}

// Check user authentication
if (!isset($_SESSION['app_user_token']) || empty($_SESSION['app_user_token'])) {
    echo "ERROR: Missing or invalid token";
    exit;
}

$result = "ERROR";

// Prepare SQL query based on operation type
if ($modify == 1) {
    $stmt = $dbb->prepare('UPDATE wh_db_persistency SET type = ? WHERE id = ?');
    $dbb->set_charset("utf8");
    $stmt->bind_param('si', $type, $id);
} else {
    $stmt = $dbb->prepare('INSERT INTO wh_db_persistency (type) VALUES (?)');
    $dbb->set_charset("utf8");
    $stmt->bind_param('s', $type);
}

// Execute query and handle result
if ($stmt->execute()) {
    header("Location: ../../index.php?opcion=saas_persistency&error=0"); // Success
    exit;
} else {
    header("Location: ../../index.php?opcion=saas_persistency&error=1"); // Error
    exit;
}

echo $result;
?>
