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
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $name = StringInputCleaner($name);

    $location = isset($_POST['location']) ? trim($_POST['location']) : '';
    $location = StringInputCleaner($location);

    $networkProvider = isset($_POST['networkProvider']) ? trim($_POST['networkProvider']) : '';
    $networkProvider = StringInputCleaner($networkProvider);
}

// Check user authentication
if (!isset($_SESSION['app_user_token']) || empty($_SESSION['app_user_token'])) {
    echo "ERROR: Missing or invalid token";
    exit;
}

$result = "ERROR";

// Prepare SQL query based on operation type
if ($modify == 1) {
    $stmt = $dbb->prepare('UPDATE wh_datacenter SET name = ?, location = ?, networkProvider = ? WHERE id = ?');
    $dbb->set_charset("utf8");
    $stmt->bind_param('sssi', $name, $location, $networkProvider, $id);
} else {
    $stmt = $dbb->prepare('INSERT INTO wh_datacenter (name, location, networkProvider) VALUES (?, ?, ?)');
    $dbb->set_charset("utf8");
    $stmt->bind_param('sss', $name, $location, $networkProvider);
}

// Execute query and handle result
if ($stmt->execute()) {
    header("Location: ../../index.php?opcion=saas_datacenter&error=0"); // Success
    exit;
} else {
    header("Location: ../../index.php?opcion=saas_datacenter&error=1"); // Error
    exit;
}

echo $result;
?>
