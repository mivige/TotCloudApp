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

    $endpoint = isset($_POST['endpoint']) ? trim($_POST['endpoint']) : '';
    $endpoint = StringInputCleaner($endpoint);

    $cacheExpiration = isset($_POST['cacheExpiration']) ? trim($_POST['cacheExpiration']) : '';
    $cacheExpiration = StringInputCleaner($cacheExpiration);

    $bandwidth = isset($_POST['bandwidth']) ? trim($_POST['bandwidth']) : '';
    $bandwidth = StringInputCleaner($bandwidth);
}

// Check user authentication
if (!isset($_SESSION['app_user_token']) || empty($_SESSION['app_user_token'])) {
    echo "ERROR: Missing or invalid token";
    exit;
}

$result = "ERROR";

// Prepare SQL query based on operation type
if ($modify == 1) {
    $stmt = $dbb->prepare('UPDATE wh_cdn SET name = ?, endpoint = ?, cacheExpiration = ?, bandwidth = ? WHERE id = ?');
    $dbb->set_charset("utf8");
    $stmt->bind_param('ssiii', $name, $endpoint, $cacheExpiration, $bandwidth, $id);
} else {
    $stmt = $dbb->prepare('INSERT INTO wh_cdn (name, endpoint, cacheExpiration, bandwidth) VALUES (?, ?, ?, ?)');
    $dbb->set_charset("utf8");
    $stmt->bind_param('ssii', $name, $endpoint, $cacheExpiration, $bandwidth);
}

// Execute query and handle result
if ($stmt->execute()) {
    header("Location: ../../index.php?opcion=saas_cdn&error=0"); // Success
    exit;
} else {
    header("Location: ../../index.php?opcion=saas_cdn&error=1"); // Error
    exit;
}

echo $result;
?>
