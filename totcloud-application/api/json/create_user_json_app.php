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





function enviar_email_create_user($codigo_email,$to_email,$codigo_token){
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
 $template = file_get_contents("templates/enviar_codigo2_new_user.html");

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


function emailExists($dbb,$email){
 
    // query to check if email exists
    $stmt = $dbb->prepare('SELECT id, firstname, lastname, lastname2,mobile_phone,password,token,email_code,sms_code,email  FROM user    WHERE active>=0 and email = ? LIMIT 0,1');
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
		$codigo_email = $row['email_code'];
		$codigo_sms = $row['sms_code'];
        $password = $row['password'];
 
        // return true because email exists in the database
        return true;
    }
 
    // return false if email does not exist in the database
    return false;
}
 




$data = json_decode(file_get_contents("php://input"));
 
//// set product property values
if (isset($data->firstname)){$firstname = $data->firstname;}
if (isset($data->lastname)){$lastname = $data->lastname;}
if (isset($data->lastname2)){$lastname2 = $data->lastname2;}
if (isset($data->mobile_completo)){
	$mobile_phone=str_replace("+","00",$data->mobile_completo);
}
if (isset($data->email)){$email = $data->email;}
if (isset($data->password)){$password = $data->password;}

$firstname =StringInputCleaner($firstname);
$lastname =StringInputCleaner($lastname);
$lastname2 =StringInputCleaner($lastname2);
$mobile_phone =StringInputCleaner($mobile_phone);
$email =StringInputCleaner($email);
$password =StringInputCleaner($password);
 
if(
   !empty($firstname) &&
	!empty($lastname) &&
    !empty($email) &&
	!empty($mobile_phone) &&
    !empty($password) 
	&&  !emailExists($dbb,$email)) { 
	
	
 
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
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

     $query = "INSERT INTO user (firstname,lastname,lastname2,mobile_phone,email,token,email_code,sms_code,password,token_date) values ";
	 $query=$query." (?,?,?,?,?,?,?,?,?,?)" ;  
     $stmt = $dbb->prepare($query);
	 $dbb->set_charset("utf8");
	 $fecha_token=date("Y-m-d H:i:s");

	 $stmt->bind_param("ssssssssss", $firstname, $lastname, $lastname2,$mobile_phone,$email,$token,$codigo_email,$codigo_sms,$password_hash,$fecha_token);
	
 	  
	 if ($stmt->execute()) {	
        $id=$dbb->insert_id;
        $cadena_token="u=".$id."&token=".$token;
 

       //enviar_email_create_user($codigo_email,$email,$cadena_token);
       enviar_sms_create_user($codigo_sms,$codigo_email,$email,$mobile_phone);
    
	// set response code
	header('Content-Type: application/json');
    $datos = array('estado' => 'ok','message' => "User has been created.",'cadena_token' => $cadena_token);
    http_response_code(200);
    echo json_encode($datos,JSON_FORCE_OBJECT);
}
 
// message if unable to create user
else{
 
    header('Content-Type: application/json');
    
    $datos = array('estado' => 'ok','message' => "User has not been created <br>");
    http_response_code(400);
    echo json_encode($datos ,JSON_FORCE_OBJECT);
} 
	}else{
		header('Content-Type: application/json');
        $datos = array('estado' => 'ok','message' => "There is already a user with this email <br>");
        http_response_code(400);
        echo json_encode($datos ,JSON_FORCE_OBJECT);	
	}
?>