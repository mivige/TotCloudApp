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
	$code=isset($_POST['code']) ? trim($_POST['code']) : '';
    $code=StringInputCleaner($code);

    //if ($activo!=1){$activo=0;}

}


if(!isset($_SESSION['app_user_token']) || empty($_SESSION['app_user_token'])) {
			echo "ERROR DE TOKEN";
			exit;
}

$resultado="ERROR";   
   if($modificar==1){
    $stmt = $dbb->prepare('update roles  set role_name=?,description=?,code=? where  id=?  ');
	$dbb->set_charset("utf8");
	$stmt->bind_param('sssd',$nombre,$descripcion,$code,$id);
   }else{
    $stmt = $dbb->prepare('insert into roles (role_name,description,code) values (?,?,?) ');
	$dbb->set_charset("utf8");
	$stmt->bind_param('sss',$nombre,$descripcion,$code);

   }
	if ($stmt->execute()){
		header("Location: ../../index.php?opcion=roles&error=2");
        exit; 
	} else 
	{
		header("Location: ../../index.php?opcion=roles&error=1");
	$resultado = 'KO'; 	
	exit; 
	}
	echo $resultado;
?>