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
    
    $firstname=isset($_POST['firstname']) ? trim($_POST['firstname']) : '';
    $firstname=StringInputCleaner($firstname);
    $lastname=isset($_POST['lastname']) ? trim($_POST['lastname']) : '';
    $lastname=StringInputCleaner($lastname);
    $lastname2=isset($_POST['lastname2']) ? trim($_POST['lastname2']) : '';
    $lastname2=StringInputCleaner($lastname2);
    $email=isset($_POST['email']) ? trim($_POST['email']) : '';
    $email=StringInputCleaner($email);
    $mobile_phone=isset($_POST['mobile_phone']) ? trim($_POST['mobile_phone']) : '';
    $mobile_phone=StringInputCleaner($mobile_phone);      
    if (isset($_POST['activo'])) {$activo=1;} else {$activo=0;}

    //if ($activo!=1){$activo=0;}

}


if(!isset($_SESSION['app_user_token']) || empty($_SESSION['app_user_token'])) {
			echo "ERROR DE TOKEN";
			exit;
}

$resultado="ERROR";   
   if($modificar==1){
    $stmt = $dbb->prepare('update users  set firstname=?,lastname=?,lastname2=?,email=?,mobile_phone=?,activo=?,validated_email=1 where  id=?  ');
	$dbb->set_charset("utf8");
	$stmt->bind_param('ssssssd',$firstname,$lastname,$lastname2,$email,$mobile_phone,$activo,$id);
   }else{
    header("Location: ../../index.php?opcion=users&error=1");
    //$stmt = $dbb->prepare('insert into incidencias (titulo,descripcion,estado,fecha_creacion) values (?,?,?,?) ');
	//$dbb->set_charset("utf8");
	//$stmt->bind_param('ssss',$titulo,$descripcion,$estado,$fecha_creacion);

   }
	if ($stmt->execute()){
		header("Location: ../../index.php?opcion=users&error=2");
        exit; 
	} else 
	{
        header("Location: ../../index.php?opcion=users&error=1");
	$resultado = 'KO'; 	
    exit;
	}
	echo $resultado;
?>