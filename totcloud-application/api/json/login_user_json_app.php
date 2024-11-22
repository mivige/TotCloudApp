<?php
include_once '../https_redirect.php';
session_start();
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database_app.php';
date_default_timezone_set('UTC');

include_once "../config/variables.php";




function sanitize_my_email($field) {
    $field = filter_var($field, FILTER_SANITIZE_EMAIL);
    if (filter_var($field, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}


$firstname = "";
$lastname = "";
$lastname2 = "";
$mobile_phone="";
//$user->mobile_phone = $data->mobile_phone;
$email = "";
$password = "";
$token = "";
$email = "";
$codigo_sms = "";



 



$data = json_decode(file_get_contents("php://input"));
 
//// set product property values
if (isset($data->email)){$email = $data->email;}
if (isset($data->password)){$password = $data->password;}



 
if(
   !empty($email) &&
	!empty($password)) 
    
	 {   
	 
	    
	     $stmt = $dbb->prepare('SELECT * FROM user    WHERE activo>0 and validated_email=1 and solicitud_cambio_password=0 and numero_intentos_login<3 and  email= ? ');
         $dbb->set_charset("utf8");
		 $stmt->bind_param('s',$email);
         $stmt->execute();
         $result = $stmt->get_result();
	     if ($row = $result->fetch_assoc()) {
			 $numero_intentos_login=$row['numero_intentos_login'];
             $token = openssl_random_pseudo_bytes(32);
            //Convert the binary data into hexadecimal representation.
             $token = bin2hex($token);
			 $id = $row['id'];			
			 $firstname = $row['firstname'];
			 $lastname = $row['lastname'];
			 $lastname2 = $row['lastname2'];
			 $mobile_phone= $row['mobile_phone'];
			 $email =  $row['email'];
			 $password_hash  = $row['password'];
			 $fecha_cambio_password  = $row['fecha_cambio_password'];
			 
             
	         if (!password_verify($password,$password_hash)) {
			     
		 		 if ($numero_intentos_login>=2){
		 		    $stmt = $dbb->prepare('update user set numero_intentos_login=3  where id= ?');
                    $dbb->set_charset("utf8");
					$stmt->bind_param('s', $id);
                    if ($stmt->execute()){
		 	          http_response_code(400);
                      echo json_encode(array("message" => "Ha sobrepasado el número de intentos. Cuenta bloqueada"));
				    } else {
					  echo json_encode(array("message" => "Error al actualizar datos del usuario"));  
				    }
				 } else {
		 		    $stmt = $dbb->prepare('update user set numero_intentos_login=numero_intentos_login+1 where id= ?');
                    $dbb->set_charset("utf8");
					$stmt->bind_param('s', $id);
                    if ($stmt->execute()){
		 	          http_response_code(400);
					  $temp=$numero_intentos_login+1;
                      echo json_encode(array("message" => "Combinación de usuario y password incorrecta. (Lleva ".$temp." de 3 intentos)"));
				    } else {
					  echo json_encode(array("message" => "Error al actualizar datos del usuario"));					 
				    }
				 }
			 } else {
			    $fecha_token=date("Y-m-d H:i:s");
			    $stmt = $dbb->prepare('UPDATE user set activo=1=1,token=?,fecha_token=? where id= ?');
                $dbb->set_charset("utf8");
				$stmt->bind_param('sss', $token,$fecha_token,$id);
                if ($stmt->execute()){
					//Ponemos numero de intentos a 0
					 $stmt = $dbb->prepare('UPDATE user set numero_intentos_login=0 where id= ?');
                      $dbb->set_charset("utf8");
					  $stmt->bind_param('s', $id);
					  $stmt->execute();
				  //Miramos si el pwd se ha de cambiar pq han pasado xx dias
				  $datetime1 = date_create($fecha_cambio_password);
                  $datetime2 = new DateTime();
                  $interval = date_diff($datetime1, $datetime2);
                  //$diferencia_min=$interval->format('%i');	
                  //$diferencia_horas=$interval->format('%h')*60;
				  $diferencia_dias=$interval->format('%a');		
		          $diferencia_total=$diferencia_dias;
				  //$app_tiempo_validez_password=30;
				 // if (!isset($_SESSION['app_tiempo_validez_password'])){  
				  //$app_tiempo_validez_password=$_SESSION['app_tiempo_validez_password'] ;
				 // }
				  
		          if ($diferencia_total>$app_tiempo_validez_password) {
					  
					  http_response_code(200);
     	          echo json_encode(array("message" => "Password Expirado. Debe modificar el password.","u" => $id,"token" => $token,"mod_pwd" => "1"));
				  
				  }else{
					  
					  $_SESSION['app_user_token']=$token;
					  http_response_code(200);
					  
     	          echo json_encode(array("message" => "Usuario Registrado Satisfactoriamente","u" => $id,"token" => $token,"mod_pwd" => "0"));
					  
					  
				  }
					
				  
		        }else{
				  http_response_code(400);
                  echo json_encode(array("message" => "Error al actualizar datos del usuario"));
			    } 
			 }
			 
		 }else{		 
			      // set response code
				  
				  //Aqui miramos activo y password pendiente cambiar
	          $stmt = $dbb->prepare('SELECT * FROM user    WHERE activo>0  and  email= ? ');
              $dbb->set_charset("utf8");
			  $stmt->bind_param('s',$email);
              $stmt->execute();
              $result = $stmt->get_result();
	          if ($row = $result->fetch_assoc()) {
			     if ($row["numero_intentos_login"]>=3){
			      http_response_code(400);
                  echo json_encode(array("message" => "Cuenta Bloqueasa"));
			      }else{ 
			         if ($row["solicitud_cambio_password"]=="1"){
				  	    http_response_code(200);
                        echo json_encode(array("message" => " Ha solicitado un cambio de Contraseña. <br>Si no dispone del link vaya a la solicitud de nueva contraseña.","mod_pwd" => "2"));  
			         }
				  }
			  }
			   else{		  
				  
                 
	          $stmt = $dbb->prepare('SELECT * FROM user    WHERE activo=0 and email= ? ');
              $dbb->set_charset("utf8");
			  $stmt->bind_param('s',$email);
              $stmt->execute();
              $result = $stmt->get_result();
	          if ($row = $result->fetch_assoc())
			  {   if ($row["validated_email"]=="0"){ 
		  		    http_response_code(400);
                    echo json_encode(array("message" => "Acceso Denegado. <br>No ha realizado el proceso de validación de su cuenta"));
			        } else {
	   
               http_response_code(400);
               echo json_encode(array("message" => " Acceso Denegado. Usuario no registrado correctamente"));
			  }
	         } else {
				 
                $stmt = $dbb->prepare('SELECT * FROM user    WHERE activo=-1 and email= ? ');
                $dbb->set_charset("utf8");
				$stmt->bind_param('s',$email);
                $stmt->execute();
                $result = $stmt->get_result();				 
				 if ($row = $result->fetch_assoc())
			     { 
			       http_response_code(400);
                   echo json_encode(array("message" => " Cuenta Bloqueada"));
			 } else {
				 http_response_code(400);
                 echo json_encode(array("message" => "Usuario inexistente"));
			 }
			 }   
		 }
		 } 
	 } else {
		  // display message: unable to create user
    //echo json_encode(array("message" => " Ya existe un usuario con este email <br>".$data->email));
	http_response_code(400);
   echo json_encode(array("message" => " No se han pasado email y password"));
	 }
?>