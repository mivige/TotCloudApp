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

if(!isset($_SESSION['app_user_token']) || empty($_SESSION['app_user_token'])) {
			echo "ERROR DE TOKEN";
			exit;
}


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
			
		//token ya pasado el usuario debera solicitar de nuevo cambio de password
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
	$stmt1 = $dbb->prepare('update users set numero_intentos_cambio_password=numero_intentos_cambio_password+1 WHERE  id= ? ');
	$dbb->set_charset("utf8");
    $stmt1->bind_param('s' ,$u);
    $stmt1->execute();
	return false;
	}
    return false;
}

function comprobar_codigo1_codigo2_no_validado($dbb,$u,$codigo1,$codigo2){
	
	$stmt = $dbb->prepare('SELECT * FROM users    WHERE codigo_email= ? and codigo_sms= ? and id= ? and solicitud_cambio_password=1 LIMIT 0,1');
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
		  if (comprobar_codigo1_codigo2_no_validado($dbb,$u,$codigo_1,$codigo_2)	) {
	
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

           $query = "update users set password= ?, solicitud_cambio_password=0,numero_intentos_login=0, activo=1, token=?, fecha_token=?, fecha_cambio_password=? where id=?";
           $stmt = $dbb->prepare($query);
		   $dbb->set_charset("utf8");
	       $fecha_token=date("Y-m-d H:i:s");
	       $stmt->bind_param("sssss", $password_hash, $token,$fecha_token,$fecha_token, $u);
	
	        if ($stmt->execute()) {	
        
              $cadena_token="u=".$u."&token=".$token;
	          header('Content-Type: application/json');
              $datos = array('message' => "Se ha modificado la contraseña.",'cadena_token' => $cadena_token);
              http_response_code(200);
              echo json_encode($datos,JSON_FORCE_OBJECT);
            }else{
 
              header('Content-Type: application/json');
              $datos = array('message' => "No se ha podido cambiar la contraseña <br>");
              http_response_code(400);
              echo json_encode($datos ,JSON_FORCE_OBJECT);
            } 
	 
		  }else{
			  
			 http_response_code(400);
               echo json_encode(array("message" => "Esta Contraseña ya ha sido modificada anteriormente"));  
		  }
	  }else{
		  
		  
		  
         //Miramos cuantos intentos llevamos
		 $numero_intentos=0;
		 $stmt = $dbb->prepare('SELECT * FROM users    WHERE id= ? and solicitud_cambio_password=1 LIMIT 0,1');
		 $dbb->set_charset("utf8");
         $stmt->bind_param('s',$u);
         $stmt->execute();
         $result = $stmt->get_result();
	     if ($row = $result->fetch_assoc()) {
			 $numero_intentos=$row['numero_intentos_cambio_password'];
		 }
		 if ($numero_intentos>=3){
		   $stmt = $dbb->prepare('update  users set solicitud_cambio_password=0,numero_intentos_cambio_password=0 where id= ?');
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
		 
		header('Content-Type: application/json');
        $datos = array('message' => "No se ha podido cambiar la contraseña");
        http_response_code(400);
        echo json_encode($datos ,JSON_FORCE_OBJECT);		 
	 }
	 
   }else{
		header('Content-Type: application/json');
        $datos = array('message' => "No se ha podido cambiar la contraseña1".$password);
        http_response_code(400);
        echo json_encode($datos ,JSON_FORCE_OBJECT);	
	}
?>