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
    
    $price=isset($_POST['price']) ? trim($_POST['price']) : '';
    $price=StringInputCleaner($price);
    $description=isset($_POST['description']) ? trim($_POST['description']) : '';
    $description=StringInputCleaner($description);
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
    $stmt = $dbb->prepare('update ds_private_bandwidth  set price=?,description=?,code=? where  id=?  ');
	$dbb->set_charset("utf8");
	$stmt->bind_param('dssd',$price,$description,$code,$id);
   }else{
    $stmt = $dbb->prepare('insert into ds_private_bandwidth (price,description,code,currency_type) values (?,?,?,"978") ');
	$dbb->set_charset("utf8");
	$stmt->bind_param('dss',$price,$description,$code);

   }
	if ($stmt->execute()){
		header("Location: ../../index.php?opcion=paas_private_bandwidth&error=2");
        exit; 
	} else 
	{
		header("Location: ../../index.php?opcion=paas_private_bandwidth&error=1");
	$resultado = 'KO'; 	
	exit; 
	}
	echo $resultado;
?>