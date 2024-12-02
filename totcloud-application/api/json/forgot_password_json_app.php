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
	 $stmt = $dbb->prepare('SELECT id, firstname, lastname, lastname2,mobile_phone,password,token,email_code,sms_code,email  FROM user    WHERE active>=0 and email = ? LIMIT 0,1');
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
		$codigo_email = $row['email_code'];
		$codigo_sms = $row['sms_code'];
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
         $query = "update user set email_code= ?, sms_code=? ,password_change_request=1,password_change_attempts=0, password_change_request_date=?, token= ?, token_date=? where id=?";
	     $stmt = $dbb->prepare($query);
	     $dbb->set_charset("utf8");
		 $fecha_solicitud=date("Y-m-d H:i:s");
	     $stmt->bind_param("ssssss", $codigo_email, $codigo_sms, $fecha_solicitud,$token,$fecha_solicitud,$id);
	     if ($stmt->execute()){
		   $cadena_token="u=".$id."&token=".$token; 
           enviar_sms_change_pwd_user($codigo_sms,$codigo_email,$email,$mobile_phone);		 
			 
	        http_response_code(200);
			$cadena_token="u=".$id."&token=".$token;
            echo json_encode(array("message" => " Request made successfully","cadena_token" => $cadena_token));
		 } else {
	        http_response_code(200);
            echo json_encode(array("message" => " Error in request"));			 
		 }
	} else 
	{
		
       http_response_code(400);
       echo json_encode(array("message" => " Incorrect Email"));		
		
	}

	   
	}else{
		
	   // set response code
       http_response_code(400);
       echo json_encode(array("message" => " Incorrect Email"));	
	}
?>