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

//if ((!isset($_SESSION['app_titulo_principal'])) || (empty($_SESSION['app_titulo_principal'])))
//{  $_SESSION['app_titulo_principal'] = "Hospital Universitario Son Llatzer";}
//if ((isset($_SESSION['app_titulo_principal'])) && (!empty($_SESSION['app_titulo_principal'])))
//{	$app_titulo_principal=$_SESSION['app_titulo_principal'];
//} else {$app_titulo_principal="Hospital Universitario Son Llatzer";}



function sanitize_my_email($field) {
    $field = filter_var($field, FILTER_SANITIZE_EMAIL);
    if (filter_var($field, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

$id = "";
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
	$stmt = $dbb->prepare('SELECT * FROM user    WHERE id= ? and token= ? LIMIT 0,1');
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
    	//borramos token ya pasado
			return false;
	   }else{
          return true;
	   }
    }
 
    // return false if email does not exist in the database
    return false;
}


function emailExists($dbb,$email){
    // query to check if email exists
    //$query = "SELECT id, firstname, lastname, lastname2,mobile_phone,password,token,codigo_email,codigo_sms,email  FROM user    WHERE email ='".$email."' LIMIT 0,1";
    $stmt = $dbb->prepare('SELECT id, firstname, lastname, lastname2,mobile_phone,password,token,codigo_email,codigo_sms,email  FROM user    WHERE activo>=0 and email = ? LIMIT 0,1');
    $dbb->set_charset("utf8");
	$stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
	if ($row = $result->fetch_assoc()) {
       // assign values to object properties
        $id = $row['id'];
        $firstname = $row['firstname'];
        $lastname = $row['lastname'];
		$lastname2 = $row['lastname2'];
		$mobile_phone = $row['mobile_phone'];
		$token = $row['token'];
		$codigo_email = $row['codigo_email'];
		$codigo_sms = $row['codigo_sms'];
        $password = $row['password'];
         // return true because email exists in the database
        return true;
    }
     // return false if email does not exist in the database
    return false;
}

function comprobar_codigo1_codigo2($dbb,$u,$codigo1,$codigo2){
	
	$stmt = $dbb->prepare('SELECT * FROM user    WHERE codigo_email= ? and codigo_sms= ? and id= ? LIMIT 0,1');
    $dbb->set_charset("utf8");
	$stmt->bind_param('sss', $codigo1,$codigo2,$u);
    $stmt->execute();
    $result = $stmt->get_result();
	if ($row = $result->fetch_assoc()) {
      return true;
    } else {
	$stmt1 = $dbb->prepare('update user set numero_intentos_validated_email=numero_intentos_validated_email+1 WHERE  id= ? ');
    $dbb->set_charset("utf8");
	$stmt1->bind_param('s' ,$u);
    $stmt1->execute();
	return false;
	}
    return false;
}

function comprobar_codigo1_codigo2_no_validado($dbb,$u,$codigo1,$codigo2){
	
	$stmt = $dbb->prepare('SELECT * FROM user    WHERE codigo_email= ? and codigo_sms= ? and id= ? and validated_email=0 LIMIT 0,1');
    $dbb->set_charset("utf8");
	$stmt->bind_param('sss', $codigo1,$codigo2,$u);
    $stmt->execute();
    $result = $stmt->get_result();
	if ($row = $result->fetch_assoc()) {
      return true;
    } else {
		//Borramos el usuario
		  $query ="delete from user where id= ?";
		   $stmt = $dbb->prepare('delete from user where id= ?');
          $dbb->set_charset("utf8");
		  $stmt->bind_param('s', $u);
          $stmt->execute();
		  return false;
	}
    return false;
}
 


function enviar_email_confirm_password($codigo_email,$to_email,$codigo_token){
  global $app_titulo_principal;
  global $app_no_reply_email;
  global $app_dominio;
  global $app_footer_copyright;
  $subject = 'Código de Registro en '.$app_titulo_principal;
  $from = $app_no_reply_email;
  // To send HTML mail, the Content-type header must be set
  $headers  = 'MIME-Version: 1.0' . "\r\n";
  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
  // Create email headers
  $headers .= 'From: '.$from."\r\n".
    'Reply-To: '.$from."\r\n" .
    'X-Mailer: PHP/' . phpversion();
  // Compose a simple HTML email message
 $variables = array();
 $variables['email'] = $to_email;
 $variables['codigo'] = $codigo_email;
 $variables['token'] = $codigo_token;
 $variables['dominio'] = $app_dominio;
 $variables['sitio'] = $app_titulo_principal;
 $variables['footer'] = $app_footer_copyright;
 $template = file_get_contents("templates/enviar_codigo2_confirm_pwd.html");

 foreach($variables as $key => $value)
 {
    $template = str_replace('{{ '.$key.' }}', $value, $template);
 }

 
 //check if the email address is invalid $secure_check
 $secure_check = sanitize_my_email($to_email);
 if ($secure_check == false) {
    return false;
 } else { //send email 
 $to_email = filter_var($to_email, FILTER_SANITIZE_EMAIL);
 if(mail($to_email, $subject, $template, $headers)){
   return true;
 } else{
    return false;
 }
}
}




$data = json_decode(file_get_contents("php://input"));
 
//// set product property values
if (isset($data->email)){$email = $data->email;}



 
if(!empty($email)) { 
	 $stmt = $dbb->prepare('SELECT id, firstname, lastname, lastname2,mobile_phone,password,token,codigo_email,codigo_sms,email  FROM user    WHERE activo>=0 and email = ? LIMIT 0,1');
    $dbb->set_charset("utf8");
	$stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
	if ($row = $result->fetch_assoc()) { 
        $id = $row['id'];
        $firstname = $row['firstname'];
        $lastname = $row['lastname'];
		$lastname2 = $row['lastname2'];
		$mobile_phone = $row['mobile_phone'];
		$token = $row['token'];
		$codigo_email = $row['codigo_email'];
		$codigo_sms = $row['codigo_sms'];
        $password = $row['password'];
	
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
         // hash the password before saving to database	  
         $query = "update user set codigo_email= ?, codigo_sms=? ,solicitud_cambio_password=1,numero_intentos_cambio_password=0, fecha_solicitud_cambio_password=?, token= ?, fecha_token=? where id=?";
	     $stmt = $dbb->prepare($query);
	     $dbb->set_charset("utf8");
		 $fecha_solicitud=date("Y-m-d H:i:s");
	     $stmt->bind_param("ssssss", $codigo_email, $codigo_sms, $fecha_solicitud,$token,$fecha_solicitud,$id);
	     if ($stmt->execute()){
		   $cadena_token="u=".$id."&token=".$token; 
	       //enviar_email_confirm_password($codigo_email,$email,$cadena_token);
           enviar_sms_change_pwd_user($codigo_sms,$email,$mobile_phone);		 
			 
	        http_response_code(200);
			$cadena_token="u=".$id."&token=".$token;
            echo json_encode(array("message" => " Solicitud realizada correctamente","cadena_token" => $cadena_token));
		 } else {
	        http_response_code(200);
            echo json_encode(array("message" => " Error en la solicitud"));			 
		 }
	} else 
	{
		
       http_response_code(400);
       echo json_encode(array("message" => " Email Incorrecto"));		
		
	}

	   
	}else{
		
	   // set response code
       http_response_code(400);
       echo json_encode(array("message" => " Email Incorrecto"));	
	}
?>