<?php 


//tiempo renovar password en dias
if ((!isset($_SESSION['app_tiempo_validez_password'])) || (empty($_SESSION['app_tiempo_validez_password'])))
{  $_SESSION['app_tiempo_validez_password'] = "30";}
if ((isset($_SESSION['app_tiempo_validez_password'])) && (!empty($_SESSION['app_tiempo_validez_password'])))
{	$app_tiempo_validez_password=$_SESSION['app_tiempo_validez_password'];
} else {$app_tiempo_validez_password="30";}

//tiempo validez token en minutos
if ((!isset($_SESSION['app_tiempo_validez_token'])) || (empty($_SESSION['app_tiempo_validez_token'])))
{  $_SESSION['app_tiempo_validez_token'] = "1115";}
if ((isset($_SESSION['app_tiempo_validez_token'])) && (!empty($_SESSION['app_tiempo_validez_token'])))
{	$app_tiempo_validez_token=$_SESSION['app_tiempo_validez_token'];
} else {$app_tiempo_validez_token="1115";}
$app_tiempo_validez_token="1115";

$_SESSION['app_titulo_principal']="TOTCLOUD";
if ((!isset($_SESSION['app_titulo_principal'])) || (empty($_SESSION['app_titulo_principal'])))
{  $_SESSION['app_titulo_principal'] = "TOTCLOUD";}
if ((isset($_SESSION['app_titulo_principal'])) && (!empty($_SESSION['app_titulo_principal'])))
{	$app_titulo_principal=$_SESSION['app_titulo_principal'];
} else {$app_titulo_principal="TOTCLOUD";}

$_SESSION['app_titulo_secundario']="Database Practice II - Course 24/25";
if ((!isset($_SESSION['app_titulo_secundario'])) || (empty($_SESSION['app_titulo_secundario'])))
{  $_SESSION['app_titulo_secundario'] = "Database Practice II - Course 24/25";}
if ((isset($_SESSION['app_titulo_secundario'])) && (!empty($_SESSION['app_titulo_secundario'])))
{	$app_titulo_secundario=$_SESSION['app_titulo_secundario'];
} else {$app_titulo_secundario="Database Practice II - Course 24/25";}


if ((!isset($_SESSION['app_dominio'])) || (empty($_SESSION['app_dominio'])))
{  $_SESSION['app_dominio'] = "http://localhost";}
if ((isset($_SESSION['app_dominio'])) && (!empty($_SESSION['app_dominio'])))
{	$app_dominio=$_SESSION['app_dominio'];
} else {$app_dominio="http://localhost";}

if ((!isset($_SESSION['app_footer_copyright'])) || (empty($_SESSION['app_footer_copyright'])))
{  $_SESSION['app_footer_copyright'] = "&copy; 2024 Totcloud. All rights reserved";}
if ((isset($_SESSION['app_footer_copyright'])) && (!empty($_SESSION['app_footer_copyright'])))
{	$app_footer_copyright=$_SESSION['app_footer_copyright'];
} else {$app_footer_copyright="&copy; 2024 Totcloud. All rights reserved";}

if ((!isset($_SESSION['app_no_reply_email'])) || (empty($_SESSION['app_no_reply_email'])))
{  $_SESSION['app_no_reply_email'] = "no-reply@gmail.es";}
if ((isset($_SESSION['app_no_reply_email'])) && (!empty($_SESSION['app_no_reply_email'])))
{	$app_no_reply_email=$_SESSION['app_no_reply_email'];
} else {$app_no_reply_email="no-reply@gmail.es";}

$_SESSION['app_remitente_sms'] = "TOTCLOUD";
if ((!isset($_SESSION['app_remitente_sms'])) || (empty($_SESSION['app_remitente_sms'])))
{  $_SESSION['app_remitente_sms'] = "TOTCLOUD";}
if ((isset($_SESSION['app_remitente_sms'])) && (!empty($_SESSION['app_remitente_sms'])))
{	$app_remitente_sms=$_SESSION['app_remitente_sms'];
} else {$app_remitente_sms="TOTCLOUD";}

//tiempo confirmar pwd en minutos
if ((!isset($_SESSION['app_tiempo_validez_confirmacion_password'])) || (empty($_SESSION['app_tiempo_validez_confirmacion_password'])))
{  $_SESSION['app_tiempo_validez_confirmacion_password'] = "60";}
if ((isset($_SESSION['app_tiempo_validez_confirmacion_password'])) && (!empty($_SESSION['app_tiempo_validez_confirmacion_password'])))
{	$app_tiempo_validez_confirmacion_password=$_SESSION['app_tiempo_validez_confirmacion_password'];
} else {$app_tiempo_validez_confirmacion_password="60";}

$key_encriptar = 'Esta es la palabra que abre todas las contraseñas';
$key1='QVBkY2Q0N1FHWFAyRHZlbUVkbWhsbGx5cXZTWXJ3WmFVWlJjZHNKOGVzZndUVE5QN2lBQUdDMytudWhicjYvVzo6gmctzx1RVtLOMeKPpe9v9Q==';
$key2='c3IvOXZodWlmUlVzVnJJay9FZ0dvWXFQMStuM0ZjQ0kzOVMyUmNWSTZsU1JYcWNCc3pweGJQMlV1S1UrakQ3eDo6dwdBqDL1k6vn4pL5brOwPA==';
$emisor_sms='TOTCLOUD';


function StringInputCleaner($data)
{
	//remove space bfore and after
	$data = trim($data); 
	//remove slashes
	$data = stripslashes($data); 
	$data=(filter_var($data, FILTER_SANITIZE_STRING));
	return $data;
}	

function mysqlCleaner($dbb,$data)
{
	$data= mysql_real_escape_string($dbb,$data);
	$data= stripslashes($data);
	return $data;
	//or in one line code 
	//return(stripslashes(mysql_real_escape_string($data)));
}	



function encrypt($data,$key)
{
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted=openssl_encrypt($data, "aes-256-cbc", $key, 0, $iv);
    // return the encrypted string with $iv joined 
    return base64_encode($encrypted."::".$iv);
}
 
/**
 * function to decrypt
 * @param string $data
 * @param string $key
 */
function decrypt($data,$key)
{
    list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 0, $iv);
}

function url_origin( $s, $use_forwarded_host = false )
{
    $ssl      = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
    $sp       = strtolower( $s['SERVER_PROTOCOL'] );
    $protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
    $port     = $s['SERVER_PORT'];
    $port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
    //$host     = ( $use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ) ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null );
    $host     = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;
    //return $protocol . '://' . $host;
	$uri = $protocol . '://' . $host . $s['REQUEST_URI'];
    $segments = explode('/',  $s['REQUEST_URI'], 3);
    $url = $segments[1];
	return $protocol . '://' . $host .'/'.$url.'/';
}

function full_url( $s, $use_forwarded_host = false )
{
    return url_origin( $s, $use_forwarded_host ) ;
}

function full_path()
{
    $s = &$_SERVER;
    $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
    $sp = strtolower($s['SERVER_PROTOCOL']);
    $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
    $port = $s['SERVER_PORT'];
    $port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
    $host = isset($s['HTTP_X_FORWARDED_HOST']) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
    $host = isset($host) ? $host : $s['SERVER_NAME'] . $port;
    $uri = $protocol . '://' . $host . $s['REQUEST_URI'];
    $segments = explode('?', $uri, 2);
    $url = $segments[0];
    return $url;
}


function enviar_sms_create_user($codigo_sms,$codigo_email,$email,$movil)
{
  global $app_titulo_principal;
  global $app_remitente_sms;

 $ch = curl_init();
//Set the URL that you want to GET by using the CURLOPT_URL option.
 $encoded_message = urlencode( "El código 2 para poder activar su cuenta: \n".$email."\nen ".$app_titulo_principal." es:\nCódigo 2: ".$codigo_sms."\nTambién necesitará el Código 1 recibido en su email(".$codigo_email.").\nGracias por utilizar ".$app_titulo_principal);
 curl_setopt($ch, CURLOPT_URL, "https://www.ovh.com/cgi-bin/sms/http2sms.cgi?account=sms-ct14388-1&login=tcontest&password=XtrM6345&from=".$app_remitente_sms."&to=".$movil."&message=".$encoded_message);
 //Set CURLOPT_RETURNTRANSFER so that the content is returned as a variable.
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 //Set CURLOPT_FOLLOWLOCATION to true to follow redirects.
 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
 //Execute the request.
 $data = curl_exec($ch);
 //Close the cURL handle.
 curl_close($ch);
}

function enviar_sms_change_pwd_user($codigo_sms,$email,$movil)
{
  global $app_titulo_principal;
  global $app_remitente_sms;

 $ch = curl_init();
//Set the URL that you want to GET by using the CURLOPT_URL option.
 $encoded_message = urlencode( "El código 2 para poder cambiar el password de su cuenta: \n".$email."\nen ".$app_titulo_principal." es:\nCódigo 2: ".$codigo_sms."\nTambién necesitará el Código 1 recibido en su email.\nGracias por utilizar ".$app_titulo_principal);
 curl_setopt($ch, CURLOPT_URL, "https://www.ovh.com/cgi-bin/sms/http2sms.cgi?account=sms-ct14388-1&login=tcontest&password=XtrM6345&from=".$app_remitente_sms."&to=".$movil."&message=".$encoded_message);
 //Set CURLOPT_RETURNTRANSFER so that the content is returned as a variable.
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 //Set CURLOPT_FOLLOWLOCATION to true to follow redirects.
 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
 //Execute the request.
 $data = curl_exec($ch);
 //Close the cURL handle.
 curl_close($ch);
}


?>