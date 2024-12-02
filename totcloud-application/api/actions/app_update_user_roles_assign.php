<?php
include_once '../https_redirect.php';
session_start();
include_once '../config/database_app.php';
date_default_timezone_set('UTC');
include_once "../config/variables.php";

$modificar= isset($_POST['modificar']) ? trim($_POST['modificar']) : '';
$id= isset($_POST['id']) ? trim($_POST['id']) : '';
if( empty($id) && $modificar==1) {
			echo "ERROR DE ID";
   			exit;
} else {
    
    $role=isset($_POST['role']) ? trim($_POST['role']) : '';
    $role=StringInputCleaner($role);
    

    //if ($activo!=1){$activo=0;}

}


if(!isset($_SESSION['app_user_token']) || empty($_SESSION['app_user_token'])) {
			echo "TOKEN ERROR";
			exit;
}

$resultado="ERROR";   
   if($modificar==0){
    $stmt = $dbb->prepare('insert into u_user_x_role (user_id,role_id) values (?,?) ');
	$dbb->set_charset("utf8");
	$stmt->bind_param('ss',$id,$role);


   }
	if ($stmt->execute()){
		header("Location: ../../index.php?opcion=users_roles&id=".$id."&error=2");
        exit; 
	} else 
	{
		header("Location: ../../index.php?opcion=users_roles&id=".$id."&error=1");
	$resultado = 'KO'; 	
	exit; 
	}
	echo $resultado;
?>