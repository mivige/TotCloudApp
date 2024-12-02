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
    
    $processor=isset($_POST['processor']) ? trim($_POST['processor']) : '';
    $processor=StringInputCleaner($processor);
    $memory=isset($_POST['memory']) ? trim($_POST['memory']) : '';
    $memory=StringInputCleaner($memory);
    $private_bandwith=isset($_POST['private_bandwith']) ? trim($_POST['private_bandwith']) : '';
    $private_bandwith=StringInputCleaner($private_bandwith);
    $public_bandwith=isset($_POST['public_bandwith']) ? trim($_POST['public_bandwith']) : '';
    $public_bandwith=StringInputCleaner($public_bandwith);
    $storage=isset($_POST['storage']) ? trim($_POST['storage']) : '';
    $storage=StringInputCleaner($storage);
    $os=isset($_POST['os']) ? trim($_POST['os']) : '';
    $os=StringInputCleaner($os);
    $datacenterregion=isset($_POST['datacenterregion']) ? trim($_POST['datacenterregion']) : '';
    $datacenterregion=StringInputCleaner($datacenterregion);
    $commitment_period=isset($_POST['commitment_period']) ? trim($_POST['commitment_period']) : '';
    $commitment_period=StringInputCleaner($commitment_period);
    $id_user=isset($_POST['id_user']) ? trim($_POST['id_user']) : '';
    $id_user=StringInputCleaner($id_user);   
    $estado=isset($_POST['estado']) ? trim($_POST['estado']) : '';
    $estado=StringInputCleaner($estado);
    $fecha_creacion=isset($_POST['fecha_creacion']) ? trim($_POST['fecha_creacion']) : '';
    $fecha_creacion=StringInputCleaner($fecha_creacion);

    $request_id=isset($_POST['request_id']) ? trim($_POST['request_id']) : '';
    $request_id=StringInputCleaner($request_id);
    $state=isset($_POST['state']) ? trim($_POST['state']) : '';
    $state=StringInputCleaner($state);

}


if(!isset($_SESSION['app_user_token']) || empty($_SESSION['app_user_token'])) {
    echo "TOKEN ERROR";
    exit;
}

$resultado="ERROR";   
   if($modificar==1){
    $stmt = $dbb->prepare('update paas_dedicated_server  set public_bandwidth_code=?,private_bandwidth_code=?,storage_code=?,memory_code=?,processor_code=?,data_center_region_code=?,os_code=?,commitment_period=? where  id=?  ');
	$dbb->set_charset("utf8");
	$stmt->bind_param('sssssssss',$public_bandwith,$private_bandwith,$storage,$memory,$processor,$datacenterregion,$os,$commitment_period,$id);





    if ($stmt->execute()){

        $stmt = $dbb->prepare('update request  set state=? where  request_id=?  ');
        $dbb->set_charset("utf8");
        $stmt->bind_param('ss',$state,$request_id);
    
        if ($stmt->execute()){


		header("Location: ../../index.php?opcion=requests&error=2");
        }else{

            header("Location: ../../index.php?opcion=requests&error=3");
        }
        }else{
          //echo $private_bandwith."-".$public_bandwith."-".$storage."-".$memory."-".$processor."-".$datacenterregion."-".$os."-".$commitment_period."-".$id;
            header("Location: ../../index.php?opcion=requests&error=3");
        }
        exit; 


   }else{


    $fecha_request=date("Y-m-d H:i:s");
    $stmt = $dbb->prepare('insert into request (date,user_id) values (?,?) ');
    $dbb->set_charset("utf8");
    $stmt->bind_param('ss',$fecha_request,$id_user);



    if ($stmt->execute()){
        $request_id=$dbb->insert_id;

        $stmt = $dbb->prepare('insert into paas_dedicated_server (request_id,category_code,public_bandwidth_code,private_bandwidth_code,storage_code,memory_code,processor_code,data_center_region_code,os_code,commitment_period) values (?,"DDS001",?,?,?,?,?,?,?,?) ');
        $dbb->set_charset("utf8");
        $stmt->bind_param('dssssssss',$request_id,$public_bandwith,$private_bandwith,$storage,$memory,$processor,$datacenterregion,$os,$commitment_period);

        if ($stmt->execute()){
		header("Location: ../../index.php?opcion=requests&error=2");
        }else{
          
            header("Location: ../../index.php?opcion=requests&error=3");
        }
        exit; 
	} else 
	{
        header("Location: ../../index.php?opcion=requests&error=1");
        exit; 
	$resultado = 'KO'; 	
	}


   }
	
	echo $resultado;
?>