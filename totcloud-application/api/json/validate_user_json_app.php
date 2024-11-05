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


function estokenvalido($dbb,$token,$u){
	
	$stmt = $dbb->prepare('SELECT * FROM users    WHERE id= ? and token= ? LIMIT 0,1');
$dbb->set_charset("utf8");   
   $stmt->bind_param('ss', $u,$token);
    $stmt->execute();
    $result = $stmt->get_result();
	if ($row = $result->fetch_assoc()) {
		
		//Miramos validez fecha token
		$fecha=$row['fecha_token'];
		
        $datetime1 = date_create($fecha);
        $datetime2 = new DateTime();
        $interval = date_diff($datetime1, $datetime2);
        $diferencia_min=$interval->format('%i');	
        $diferencia_horas=$interval->format('%h')*60;
        $diferencia_dias=$interval->format('%a')*24*60;		
		$diferencia_total=$diferencia_min+$diferencia_dias+$diferencia_horas;
		if ($diferencia_total>1500) {
		  $stmt = $dbb->prepare('delete from users where id= ?');
          $dbb->set_charset("utf8");
		  $stmt->bind_param('s', $u);
          $stmt->execute();
		return false;
	   }else{
        return true;
	   }
    }
 
    // return false if email does not exist in the database
    return false;
}

function comprobar_codigo1_codigo2($dbb,$u,$codigo1,$codigo2){
	
	$stmt = $dbb->prepare('SELECT * FROM users    WHERE codigo_email= ? and codigo_sms= ?  and id= ? LIMIT 0,1');
    $dbb->set_charset("utf8");
	$stmt->bind_param('sss', $codigo1,$codigo2,$u);
    $stmt->execute();
    $result = $stmt->get_result();
	if ($row = $result->fetch_assoc()) {
      return true;
    } else {
	$stmt1 = $dbb->prepare('update users set numero_intentos_validated_email=numero_intentos_validated_email+1 WHERE  id= ? ');
    $dbb->set_charset("utf8");
	$stmt1->bind_param('s' ,$u);
    $stmt1->execute();
	return false;
	}
    return false;
}

function comprobar_codigo1_codigo2_no_validado($dbb,$u,$codigo1,$codigo2){
	
	$stmt = $dbb->prepare('SELECT * FROM users    WHERE codigo_email= ? and codigo_sms= ? and id= ? and validated_email=0 LIMIT 0,1');
    $dbb->set_charset("utf8");
	$stmt->bind_param('sss', $codigo1,$codigo2,$u);
    $stmt->execute();
    $result = $stmt->get_result();
	if ($row = $result->fetch_assoc()) {
      return true;
    } else {
		//Borramos el usuario
		  //$query ="delete from telemed_users where id= ?";
		  //$stmt = $dbb->prepare('delete from telemed_users where id= ?');
          //$stmt->bind_param('s', $u);
          //$stmt->execute();
		  //return false;
	}
    return false;
}
 



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
  	     if (comprobar_codigo1_codigo2_no_validado($dbb,$u,$codigo_1,$codigo_2)	) {
            $token = openssl_random_pseudo_bytes(32);
            //Convert the binary data into hexadecimal representation.
            $token = bin2hex($token);	  
		    $query ="UPDATE users set validated_email=1,token=? where id= ?";
		    $fecha_token=date("Y-m-d H:i:s");
		    $stmt = $dbb->prepare('UPDATE users set activo=1,validated_email=1,token=?,fecha_token=?,fecha_cambio_password=? where id= ?');
            $dbb->set_charset("utf8");
			$stmt->bind_param('ssss', $token,$fecha_token,$fecha_token,$u);
            if ($stmt->execute()){
		      http_response_code(200);
     	      echo json_encode(array("message" => "Ya puede acceder con su login y password","u" => $u,"token" => $token));
		    } else {
			  http_response_code(400);
              echo json_encode(array("message" => "No se ha podido validar el usuario"));
		    }
		    }else{
               http_response_code(400);
               echo json_encode(array("message" => "Este Usuario ya ha sido Validado anteriormente"));
		    }
       }else{
         //Miramos cuantos intentos llevamos
		 $numero_intentos=0;
		 $stmt = $dbb->prepare('SELECT * FROM users    WHERE id= ? and validated_email=0 LIMIT 0,1');
         $dbb->set_charset("utf8");
		 $stmt->bind_param('s',$u);
         $stmt->execute();
         $result = $stmt->get_result();
	     if ($row = $result->fetch_assoc()) {
			 $numero_intentos=$row['numero_intentos_validated_email'];
		 }
		 if ($numero_intentos>=3){
		   $stmt = $dbb->prepare('delete from users where id= ?');
           $dbb->set_charset("utf8");
		   $stmt->bind_param('s', $u);
           $stmt->execute();
		 }
         http_response_code(400);
         if ($numero_intentos>=3){
          echo json_encode(array("message" => "Ha superado el número máximo de intentos"));
		 }else{
			  echo json_encode(array("message" => "Los Códigos introducidos son Incorrectos(Lleva ".$numero_intentos." de 3 intentos)"));
		 }
       } 
	}else{
       http_response_code(400);
       echo json_encode(array("message" => " Token no válido"));	
	}
?>