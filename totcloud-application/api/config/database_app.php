<?php
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'totcloud_db';
$dbb = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName) or die("Error " . mysqli_error($dbb));
?>