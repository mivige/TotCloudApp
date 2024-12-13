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
if (isset($data->token)){$token = $data->token;}
if (isset($data->u)){$u = $data->u;}
if (isset($data->codigo_1)){$codigo_1 = $data->codigo_1;}
if (isset($data->codigo_2)){$codigo_2 = $data->codigo_2;}


 
if(
   !empty($token) &&
	!empty($u) &&
    !empty($codigo_1) &&
	!empty($codigo_2)
    
	&&  estokenvalido($dbb,$token,$u)) { 
	  
	   if (comprobar_codigo1_codigo2($dbb,$u,$codigo_1,$codigo_2)	) {
  	     if (comprobar_codigo1_codigo2_no_validado($dbb,$u,$codigo_1,$codigo_2,2)	) {
            $token = openssl_random_pseudo_bytes(32);
            //Convert the binary data into hexadecimal representation.
            $token = bin2hex($token);	  
		    $query ="UPDATE user set email_verified=1,token=? where id= ?";
		    $fecha_token=date("Y-m-d H:i:s");
		    $stmt = $dbb->prepare('UPDATE user set active=1,email_verified=1,token=?,token_date=?,password_change_date=? where id= ?');
            $dbb->set_charset("utf8");
			$stmt->bind_param('ssss', $token,$fecha_token,$fecha_token,$u);
            if ($stmt->execute()){
		      http_response_code(200);
     	      echo json_encode(array("message" => "You can now access with your login and password","u" => $u,"token" => $token));
		    } else {
			  http_response_code(400);
              echo json_encode(array("message" => "The user could not be validated."));
		    }
		    }else{
               http_response_code(400);
               echo json_encode(array("message" => "This User has already been Validated previously"));
		    }
       }else{
         //Miramos cuantos intentos llevamos
		 $numero_intentos=0;
		 $stmt = $dbb->prepare('SELECT email_validation_attempts FROM user    WHERE id= ? and email_verified=0 LIMIT 0,1');
         $dbb->set_charset("utf8");
		 $stmt->bind_param('s',$u);
         $stmt->execute();
         $result = $stmt->get_result();
	     if ($row = $result->fetch_assoc()) {
			 $numero_intentos=$row['email_validation_attempts'];
		 }
		 if ($numero_intentos>=3){
		   $stmt = $dbb->prepare('delete from user where id= ?');
           $dbb->set_charset("utf8");
		   $stmt->bind_param('s', $u);
           $stmt->execute();
		 }
         http_response_code(400);
         if ($numero_intentos>=3){
          echo json_encode(array("message" => "You have exceeded the maximum number of attempts"));
		 }else{
			  echo json_encode(array("message" => "The codes entered are incorrect(It takes ".$numero_intentos." out of 3 attempts)"));
		 }
       } 
	}else{
       http_response_code(400);
       echo json_encode(array("message" => " Invalid token"));	
	}
?>