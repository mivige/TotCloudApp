<?php
include_once 'https_redirect.php';
session_start();
if((isset($_SESSION['app_user_token'])) && (!empty($_SESSION['app_user_token']))) {
	      session_destroy();
		  unset($_SESSION['app_user_token']);
			header("Location: app_login.php");
			exit;
} else {header("Location: index.php");};


?>