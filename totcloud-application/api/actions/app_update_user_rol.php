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
    
    $nombre=isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $nombre=StringInputCleaner($nombre);
    $descripcion=isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';
    $descripcion=StringInputCleaner($descripcion);
    

    //if ($activo!=1){$activo=0;}

}


if(!isset($_SESSION['app_user_token']) || empty($_SESSION['app_user_token'])) {
			echo "ERROR DE TOKEN";
			exit;
}

$resultado="ERROR";   
   if($modificar==1){
    $stmt = $dbb->prepare('update roles  set role_name=?,description=? where  id=?  ');
	$dbb->set_charset("utf8");
	$stmt->bind_param('ssd',$nombre,$descripcion,$id);
   }else{
    $stmt = $dbb->prepare('insert into roles (role_name,description) values (?,?) ');
	$dbb->set_charset("utf8");
	$stmt->bind_param('ss',$nombre,$descripcion);

   }
	if ($stmt->execute()){
		header("Location: ../../index.php?opcion=roles");
        exit; 
	} else 
	{
	$resultado = 'KO'; 	
	}
	echo $resultado;
?>