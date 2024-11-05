<?php
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'practica_bdds';
$dbb = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName) or die("Error " . mysqli_error($dbb));
?>