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
    $provider = isset($_POST['provider']) ? trim($_POST['provider']) : '';
    $provider = StringInputCleaner($provider);

    $validationLevel = isset($_POST['validationLevel']) ? trim($_POST['validationLevel']) : '';
    $validationLevel = StringInputCleaner($validationLevel);

    $certificateType = isset($_POST['certificateType']) ? trim($_POST['certificateType']) : '';
    $certificateType = StringInputCleaner($certificateType);

    $expirationDate = isset($_POST['expirationDate']) ? trim($_POST['expirationDate']) : '';
    $expirationDate = StringInputCleaner($expirationDate);

    $price = isset($_POST['price']) ? trim($_POST['price']) : '';
    $price = StringInputCleaner($price);
}

// Check user authentication
if (!isset($_SESSION['app_user_token']) || empty($_SESSION['app_user_token'])) {
    echo "ERROR: Missing or invalid token";
    exit;
}

$result = "ERROR";

// Prepare SQL query based on operation type
if ($modify == 1) {
    $stmt = $dbb->prepare('UPDATE wh_ssl SET provider = ?, validationLevel = ?, certificateType = ?, expirationDate = ?, price = ? WHERE id = ?');
    $dbb->set_charset("utf8");
    $stmt->bind_param('ssssdi', $provider, $validationLevel, $certificateType, $expirationDate, $price, $id);
} else {
    $stmt = $dbb->prepare('INSERT INTO wh_ssl (provider, validationLevel, certificateType, expirationDate, price) VALUES (?, ?, ?, ?, ?)');
    $dbb->set_charset("utf8");
    $stmt->bind_param('ssssd', $provider, $validationLevel, $certificateType, $expirationDate, $price);
}

// Execute query and handle result
if ($stmt->execute()) {
    header("Location: ../../index.php?opcion=saas_ssl&error=0"); // Success
    exit;
} else {
    header("Location: ../../index.php?opcion=saas_ssl&error=1"); // Error
    exit;
}

echo $result;
?>
