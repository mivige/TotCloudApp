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



function comprobar_pwd_history($dbb,$password,$u){
	
	$stmt = $dbb->prepare('SELECT * FROM u_password_history    WHERE user_id= ?  ');
	$dbb->set_charset("utf8");
    $stmt->bind_param('i', $u);
    $stmt->execute();
    $result = $stmt->get_result();
    $already_used = false;

    while ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $already_used = true;
            break;
        }
    }

    $stmt = $dbb->prepare('SELECT * FROM user   WHERE id= ?  ');
    $dbb->set_charset("utf8");
      $stmt->bind_param('i', $u);
      $stmt->execute();
      $result = $stmt->get_result();
     
  
      while ($row = $result->fetch_assoc()) {
          if (password_verify($password, $row['password'])) {
              $already_used = true;
              break;
          }
      }


    return$already_used;
}

$codigo_1 ="";
$codigo_2 ="";
$password ="";
$u ="";
$token ="";

$data = json_decode(file_get_contents("php://input"));
 
//// set product property values
if (isset($data->codigo_1)){$codigo_1 = $data->codigo_1;}
if (isset($data->codigo_2)){$codigo_2 = $data->codigo_2;}
if (isset($data->password)){$password = $data->password;}
if (isset($data->u)){$u = $data->u;}
if (isset($data->token)){$token = $data->token;}

//$codigo_1 =StringInputCleaner($codigo_1);
//$codigo_2 =StringInputCleaner($codigo_2);
//$password =StringInputCleaner($password);
//$u =StringInputCleaner($u);
//$token =StringInputCleaner($token);
 
if(
   (!empty($token) &&
   !empty($u) &&
   !empty($codigo_1)&&
   !empty($codigo_2)&&
   !empty($password))
	) { 

     if (estokenvalido($dbb,$token,$u)){
    //Comprobamos que existe un usuario con el token e id indicado
	  if (comprobar_codigo1_codigo2($dbb,$u,$codigo_1,$codigo_2)){
		  if (comprobar_codigo1_codigo2_no_validado($dbb,$u,$codigo_1,$codigo_2,1)	) {

       if (!comprobar_pwd_history($dbb,$password,$u)){
	     
            //Generate a random string.
            $token = openssl_random_pseudo_bytes(32);
           //Convert the binary data into hexadecimal representation.
           $token = bin2hex($token);
           // create the user

           $codigo_email=mt_rand(100000,999999);
           $codigo_sms=mt_rand(100000,999999);

           $token = $token;
           $codigo_email = $codigo_email;
           $codigo_sms = $codigo_sms;
           $password_hash = password_hash($password, PASSWORD_BCRYPT);

           $query = "update user set password= ?, password_change_request=0,login_attempts=0, active=1, token=?, token_date=?, password_change_date=? where id=?";
           $stmt = $dbb->prepare($query);
		   $dbb->set_charset("utf8");
	       $fecha_token=date("Y-m-d H:i:s");
	       $stmt->bind_param("sssss", $password_hash, $token,$fecha_token,$fecha_token, $u);
	
	        if ($stmt->execute()) {	
        
              $cadena_token="u=".$u."&token=".$token;
	          header('Content-Type: application/json');
              $datos = array('message' => "Password has been changed.",'cadena_token' => $cadena_token);
              http_response_code(200);
              echo json_encode($datos,JSON_FORCE_OBJECT);
            }else{
 
              header('Content-Type: application/json');
              $datos = array('message' => "Failed to change password <br>");
              http_response_code(400);
              echo json_encode($datos ,JSON_FORCE_OBJECT);
            } 
          }else{

            http_response_code(400);
            echo json_encode(array("message" => "This password has already been used before"));  

          }
		  }else{
			  
			 http_response_code(400);
               echo json_encode(array("message" => "This password has already been changed before"));  
		  }
	  }else{
		  
		  
		  
         //Miramos cuantos intentos llevamos
		 $numero_intentos=0;
		 $stmt = $dbb->prepare('SELECT * FROM user    WHERE id= ? and password_change_request=1 LIMIT 0,1');
		 $dbb->set_charset("utf8");
         $stmt->bind_param('s',$u);
         $stmt->execute();
         $result = $stmt->get_result();
	     if ($row = $result->fetch_assoc()) {
			 $numero_intentos=$row['password_change_attempts'];
		 }
		 if ($numero_intentos>=3){
		   $stmt = $dbb->prepare('update  user set password_change_request=0,password_change_attempts=0 where id= ?');
		   $dbb->set_charset("utf8");
           $stmt->bind_param('s', $u);
           $stmt->execute();
		 }
         http_response_code(400);
         if ($numero_intentos>=3){
          echo json_encode(array("message" => "You have exceeded the maximum number of attempts"));
		 }else{
			  echo json_encode(array("message" => "The codes entered are incorrect (It takes ".$numero_intentos." out of 3 attempts)"));
		 }	  
		  
	  }
	 }else{
		 
		header('Content-Type: application/json');
        $datos = array('message' => "Failed to change password");
        http_response_code(400);
        echo json_encode($datos ,JSON_FORCE_OBJECT);		 
	 }
	 
   }else{
		header('Content-Type: application/json');
        $datos = array('message' => "Failed to change password".$password);
        http_response_code(400);
        echo json_encode($datos ,JSON_FORCE_OBJECT);	
	}
?>