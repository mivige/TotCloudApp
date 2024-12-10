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
	 
	    
	     $stmt = $dbb->prepare('SELECT * FROM user    WHERE active>0 and email_verified=1 and password_change_request=0 and login_attempts<3 and  email= ? ');
         $dbb->set_charset("utf8");
		 $stmt->bind_param('s',$email);
         $stmt->execute();
         $result = $stmt->get_result();
	     if ($row = $result->fetch_assoc()) {
			 $numero_intentos_login=$row['login_attempts'];
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
			 $fecha_cambio_password  = $row['password_change_date'];
			 
             
	         if (!password_verify($password,$password_hash)) {
			     
		 		 if ($numero_intentos_login>=2){
		 		    $stmt = $dbb->prepare('update user set login_attempts=3  where id= ?');
                    $dbb->set_charset("utf8");
					$stmt->bind_param('s', $id);
                    if ($stmt->execute()){
		 	          http_response_code(400);
                      echo json_encode(array("message" => "You have exceeded the number of attempts. Account blocked"));
				    } else {
					  echo json_encode(array("message" => "Error updating user data"));  
				    }
				 } else {
		 		    $stmt = $dbb->prepare('update user set login_attempts=login_attempts+1 where id= ?');
                    $dbb->set_charset("utf8");
					$stmt->bind_param('s', $id);
                    if ($stmt->execute()){
		 	          http_response_code(400);
					  $temp=$numero_intentos_login+1;
                      echo json_encode(array("message" => "Incorrect username and password combination.(It takes  ".$temp." out of 3 attempts)"));
				    } else {
					  echo json_encode(array("message" => "Error updating user data"));					 
				    }
				 }
			 } else {
			    $fecha_token=date("Y-m-d H:i:s");
			    $stmt = $dbb->prepare('UPDATE user set active=1=1,token=?,token_date=? where id= ?');
                $dbb->set_charset("utf8");
				$stmt->bind_param('sss', $token,$fecha_token,$id);
                if ($stmt->execute()){
					//Ponemos numero de intentos a 0
					 $stmt = $dbb->prepare('UPDATE user set login_attempts=0 where id= ?');
                      $dbb->set_charset("utf8");
					  $stmt->bind_param('s', $id);
					  $stmt->execute();
				  //Miramos si el pwd se ha de cambiar pq han pasado xx dias
				  $datetime1 = date_create($fecha_cambio_password);
                  $datetime2 = new DateTime();
                  $interval = date_diff($datetime1, $datetime2);
				  $diferencia_dias=$interval->format('%a');		
		          $diferencia_total=$diferencia_dias;
				  
		          if ($diferencia_total>$app_tiempo_validez_password) {
					  
					  http_response_code(200);
     	          echo json_encode(array("message" => "Password expired. You must change the password..","u" => $id,"token" => $token,"mod_pwd" => "1"));
				  
				  }else{
					  
					  $_SESSION['app_user_token']=$token;
					  http_response_code(200);
					  
     	          echo json_encode(array("message" => "User Successfully Registered","u" => $id,"token" => $token,"mod_pwd" => "0"));
					  
					  
				  }
					
				  
		        }else{
				  http_response_code(400);
                  echo json_encode(array("message" => "Error updating user data"));
			    } 
			 }
			 
		 }else{		 
			      // set response code
				  
				  //Aqui miramos activo y password pendiente cambiar
	          $stmt = $dbb->prepare('SELECT * FROM user    WHERE active>0  and  email= ? ');
              $dbb->set_charset("utf8");
			  $stmt->bind_param('s',$email);
              $stmt->execute();
              $result = $stmt->get_result();
	          if ($row = $result->fetch_assoc()) {
			     if ($row["login_attempts"]>=3){
			      http_response_code(400);
                  echo json_encode(array("message" => "Cuenta Bloqueada"));
			      }else{ 
			         if ($row["password_change_request"]=="1"){
				  	    http_response_code(200);
                        echo json_encode(array("message" => " You have requested a password change. <br>If you do not have the link, go to the new password request.","mod_pwd" => "2"));  
			         }
				  }
			  }
			   else{		  
				  
                 
	          $stmt = $dbb->prepare('SELECT * FROM user    WHERE active=0 and email= ? ');
              $dbb->set_charset("utf8");
			  $stmt->bind_param('s',$email);
              $stmt->execute();
              $result = $stmt->get_result();
	          if ($row = $result->fetch_assoc())
			  {   if ($row["email_verified"]=="0"){ 
		  		    http_response_code(400);
                    echo json_encode(array("message" => "Access denied. <br>You have not completed the account validation process"));
			        } else {
	   
               http_response_code(400);
               echo json_encode(array("message" => " Access denied. User not registered correctly"));
			  }
	         } else {
				 
                $stmt = $dbb->prepare('SELECT * FROM user    WHERE active=-1 and email= ? ');
                $dbb->set_charset("utf8");
				$stmt->bind_param('s',$email);
                $stmt->execute();
                $result = $stmt->get_result();				 
				 if ($row = $result->fetch_assoc())
			     { 
			       http_response_code(400);
                   echo json_encode(array("message" => " Account Blocked"));
			 } else {
				 http_response_code(400);
                 echo json_encode(array("message" => "Non-existent user"));
			 }
			 }   
		 }
		 } 
	 } else {
		  // display message: unable to create user
 	http_response_code(400);
   echo json_encode(array("message" => " Email and password have not been passed"));
	 }
?>