<?php
include_once '../https_redirect.php';
session_start();
include_once '../config/database_app.php';
date_default_timezone_set('UTC');
include_once "../config/variables.php";

if(!isset($_POST['id']) || empty($_POST['id'])) {
			echo "ERROR DE ID";
			exit;
} else {$id=$_POST['id'];}



if(!isset($_SESSION['app_user_token']) || empty($_SESSION['app_user_token'])) {
			echo "ERROR DE TOKEN";
			exit;
}

$resultado="ERROR";   
    //sustituir por borrar paas_dedicated_server
    $stmt = $dbb->prepare('delete from request  where  request_id=?  ');
	$dbb->set_charset("utf8");
	$stmt->bind_param('s',$id);
	if ($stmt->execute()){
		$resultado = 'OK';
	} else 
	{
	$resultado = 'KO'; 	
	}
	echo $resultado;
?>