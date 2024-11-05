<?php
include_once 'https_redirect.php';
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 1 Jul 2000 05:00:00 GMT"); // Fecha en el pasado
include_once 'api/config/database_app.php';
date_default_timezone_set('UTC');
session_start();

$token="";
$u="";
$variables=false;
$encontrado=false;
$fecha="";
$diferencia_total="1000";
//Tiempo de duracion del token en minutos
//Tiempo de validez del password en minutos
include_once "api/config/variables.php";







if (isset($_GET['u']) && !empty($_GET['u'])&& isset($_GET['token']) && !empty($_GET['token'])) {
  $variables=true;
  $u=$_GET['u'];
  $token=$_GET['token'];
}	


if ($variables){
    $stmt = $dbb->prepare('SELECT * FROM users    WHERE id= ? and token= ? LIMIT 0,1');
    $dbb->set_charset("utf8");
	$stmt->bind_param('ss', $u,$token);
    $stmt->execute();
    $result = $stmt->get_result();
	if ($row = $result->fetch_assoc()) {
		$encontrado=true;
		$fecha=$row['fecha_solicitud_cambio_password'];
		
        $datetime1 = date_create($fecha);
        $datetime2 = new DateTime();
        $interval = date_diff($datetime1, $datetime2);
        $diferencia_min=$interval->format('%i');	
        $diferencia_horas=$interval->format('%h')*60;
        $diferencia_dias=$interval->format('%a')*24*60;		
		$diferencia_total=$diferencia_dias+$diferencia_horas+$diferencia_min;
		
} else {$encontrado=false;}
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Confirmación Registro</title>

    <!-- Prevent the demo from appearing in search engines (REMOVE THIS) -->
    <meta name="robots" content="noindex">
<link rel="icon" type="image/png" sizes="32x32" href="assets/images/logo/favicon.png">
    <!-- Perfect Scrollbar -->
    <link type="text/css" href="assets/vendor/perfect-scrollbar.css" rel="stylesheet">

    <!-- Material Design Icons -->
    <link type="text/css" href="assets/css/material-icons.css" rel="stylesheet">
    <link type="text/css" href="assets/css/material-icons.rtl.css" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link type="text/css" href="assets/css/fontawesome.css" rel="stylesheet">
    <link type="text/css" href="assets/css/fontawesome.rtl.css" rel="stylesheet">

    <!-- App CSS -->
    <link type="text/css" href="assets/css/app.css" rel="stylesheet">
    <link type="text/css" href="assets/css/app.rtl.css" rel="stylesheet">

</head>

<body class="login">


    <div class="d-flex align-items-center" style="min-height: 100vh">
        <div class="col-sm-8 col-md-6 col-lg-4 mx-auto" style="min-width: 300px;">
            <div class="text-center mt-5 mb-1">
                     <img src="assets/images/logos_apps/logo.png"  width="35%" alt="<?php if (isset($app_titulo_principal)) {echo ($app_titulo_principal);} ?>" />
            </div>
			
			
			
			
            <div class="d-flex justify-content-center mb-5 navbar-light">
                   <h3> <?php if (isset($app_titulo_principal)) {echo ($app_titulo_principal);} ?></h3>
            </div>
            <div class="card navbar-shadow">
                <div class="card-header text-center">
                    <h4 class="card-title">Cambio de Contraseña</h4>
                    <p class="card-subtitle">Doble sistema de validación para cambio de contraseña</p>
                </div>
				
				
				
                <div class="card-body">
				
				
 	<script>
	
	  function checkPassword(str)
      {
        var re = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/;
        return re.test(str);
      }
  
  

  
	function comprobar_pwd(){
		
    var specialChars = "!@#$%^&*()-_=+[{]}\\|;:'\",<.>/?`~";
    var numbers = "0123456789";
	var cadena;
	cadena="";
		
  //if(form_log.password.value != "" ) {
      if(!checkPassword(form_log.password.value)) {
        
		
		
		if(form_log.password.value != "" && form_log.password.value == form_log.password2.value) 
		{
		  
		}
		else
		{	
		    cadena=cadena+"<span class='far fa-times-circle'></span> El password y su confirmación  no son iguales"+"<br>";
		}
		
		
		if(form_log.password.value != "" && form_log.password.value.length >5) 
		{
		} 
		else
		{
		cadena=cadena+"<span class='far fa-times-circle'></span> El password no tiene 6 o más caracteres"+"<br>";
		}
		
		
		var pwd1=/^(?=.*[A-Z])/;
		if(form_log.password.value != "" &&  pwd1.test(form_log.password.value) == false) 
		{
		cadena=cadena+"<span class='far fa-times-circle'></span> El password ha de tener al menos un letra mayúscula"+"<br>";
		} 
		else
		{
		}
		
		var pwd2=/^(?=.*[a-z])/;
		if(form_log.password.value != "" && pwd2.test(form_log.password.value) == true) 
		{
		} 
		else
		{
		cadena=cadena+"<span class='far fa-times-circle'></span> El password ha de tener al menos un letra minúscula"+"<br>";
		}		

      var pwd3=/^(?=.*[0-9])/;
		if(form_log.password.value != "" && pwd3.test(form_log.password.value) == true) 
		{
		} 
		else
		{
		cadena=cadena+"<span class='far fa-times-circle'></span> El password ha de tener al menos un número"+"<br>";
		}	  
	  
	  
	  var specialChar;
	  specialChar=false;
        for(var i=0; i<form_log.password.value.length;i++){
            for(var j=0; j<specialChars.length; j++){
                if(form_log.password.value[i]==specialChars[j]){
                    specialChar = true;
                }
            }
        }
	  		if(specialChar == true) 
		{
			} 
		else
		{
		   cadena=cadena+"<span class='far fa-times-circle'></span> El password ha de tener al menos un caracter especial"+"<br>";
		}	
		cadena=cadena+""+"<br>";
		document.getElementById("exampleModalLongTitle2").innerHTML =cadena;
		document.getElementById("exampleModalLongTitle4").innerHTML = "<img width='20%' src='assets/images/logos_apps/logo.png'>";
        $('#modal_error_pwd1').modal();
		form_log.password.focus();
		document.getElementById("btnsignup").disabled = false;
        document.getElementById("loading").style.display = "none";
        return false;
      
    } else {
		
        var specialChar;
	    specialChar=false;
        for(var i=0; i<form_log.password.value.length;i++){
            for(var j=0; j<specialChars.length; j++){
                if(form_log.password.value[i]==specialChars[j]){
                    specialChar = true;
                }
            }
        }
	  	if(specialChar == true) 
		{
			
		if(form_log.password.value != "" && form_log.password.value == form_log.password2.value) 
		{
		   return true;
		}	else {
			cadena=cadena+"<span class='far fa-times-circle'></span> El password y su confirmación no son iguales"+"<br>";
		
		   cadena=cadena+""+"<br>";
		   document.getElementById("exampleModalLongTitle2").innerHTML =cadena;
		   document.getElementById("exampleModalLongTitle4").innerHTML = "<img width='20%' src='assets/images/logos_apps/logo.png'>";
		   $('#modal_error_pwd1').modal();
           form_log.password.focus();		
		   return false;
			
		}
			
		 
		} 
		else
		{
			
		   cadena=cadena+"<span class='far fa-times-circle'></span> El password ha de tener al menos un caracter especial"+"<br>";
		
		   cadena=cadena+""+"<br>";
		   document.getElementById("exampleModalLongTitle2").innerHTML =cadena;
		   document.getElementById("exampleModalLongTitle4").innerHTML = "<img width='20%' src='assets/images/logos_apps/logo.png'>";
		   $('#modal_error_pwd1').modal();
           form_log.password.focus();		
		   return false;
		}		

    }	
	}
	
	</script>				
				
				
				<?php if (($variables) && ($encontrado) && ($diferencia_total<$app_tiempo_validez_confirmacion_password)) { ?>
				
                    <div class="alert alert-light border-1 border-left-3 border-left-primary d-flex" role="alert">
                        <i class="material-icons text-success mr-3">check_circle</i>
                        <div class="text-body">Se ha enviado un email con el Código 1 de cambio de tu contraseña y  un SMS con el Código 2 de cambio de contraseña.<br> Introduce los códigos recibidos en los campos de abajo y la nueva contraseña para poder activarla en tu cuenta.</div>
                    </div>

                    <form id="form_log" >

                        <div class="was-validated">
						<div class="form-group">
                            <label class="form-label" for="codigo_1">Código 1 recibido en el email:</label>
                            <div class="input-group input-group-merge">
						
                                <input id="codigo_1" name="codigo_1" type="text" required="" class="form-control form-control-prepended" placeholder="Introduce el Código 1">
    								<div class="invalid-feedback">Por favor proporciona tu Código 1.</div>
                                    <div class="valid-feedback">Parece Código en formato correcto</div>	                            
								<div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <span class="far fa-envelope"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
						</div>
						<div class="was-validated">
                        <div class="form-group">
                            <label class="form-label" for="codigo_2">Código 2 recibido en el Móvil:</label>
                            <div class="input-group input-group-merge">
                                <input id="codigo_2" name="codigo_2" type="text" required="" class="form-control form-control-prepended" placeholder="Introduce el Código 2">
    								<div class="invalid-feedback">Por favor proporciona tu Código 2.</div>
                                 <div class="valid-feedback">Parece Código en formato correcto</div>	                                 
								<div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <span class="far fa-envelope"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
						</div>	


        		<div class="was-validated">
                        <div class="form-group">
                            <label class="form-label" for="password">Nueva Contraseña:</label>
                            <div class="input-group input-group-merge">
                                <input id="password" name="password" type="password" required="" class="form-control form-control-appended pwd" placeholder="Your password">
							
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                      <span style="cursor:pointer" class="material-icons reveal">remove_red_eye</span>
										 
                                    </div>
                                </div>
									 <div class="invalid-feedback">Por favor introduce un password.</div>
                                 <div class="valid-feedback">Parece un password correcto!</div>
								 					 <div >
  <small >El password ha de tener al menos 8 caracteres y ha de tener al menos una letra mayúscula, una letra minúscula, un símbolo especial y un número</small>
</div>
                            </div>
                        </div>
						</div>
						
						
						
		<div class="was-validated">
                        <div class="form-group">
                            <label class="form-label" for="password">Tú password:</label>
                            <div class="input-group input-group-merge">
                                <input id="password2" name="password2" type="password" required="" class="form-control form-control-appended pwd2" placeholder="Your password">
							
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                      <span style="cursor:pointer" class="material-icons reveal2">remove_red_eye</span>
										 
                                    </div>
                                </div>
									 <div class="invalid-feedback">Por favor introduce un password.</div>
                                 <div class="valid-feedback">Parece un password correcto!</div>
                            </div>
                        </div>
						</div>
						
						
						
						<input type="hidden" id="token" name="token" class="form-control form-control-prepended" value="<?php if (isset($token)) {echo($token);}?>" >
					     <input type="hidden" id="u" name="u" class="form-control form-control-prepended" value="<?php if (isset($u)) {echo($u);}?>" >
						
					<div id="loading" style="display: none;" class="form-group text-center mb-0">
	                   <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                      <span class="sr-only">Loading...</span>
  
                       </div>
                     <p><h3>Enviando</h3></p>
                     </div>					
						<button id="btnsignup" name="signup"type="submit" class="btn btn-primary btn-block">Solicitar cambio contraseña</button>
                    </form>
					
			<!-- Modal Error EMAIL-->
					<div class="modal fade" id="modal_error_email" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
					  <div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
						
						<div class="text-center mt-5 mb-1">
						<div class="avatar avatar-xl">
						  <img src="assets/images/error.png" alt="Avatar" class="avatar-img rounded-circle">
						</div>
						</div>						
						
						  <div class="text-center mt-5 mb-1">
							  <h4 class="modal-title" id="erroremail1">ERROR AL REGISTRARTE</h5>
							  <h5 class="modal-title" id="erroremail2">&nbsp;</h5>
							  <h5 class="modal-title" id="erroremail3"><span class="far fa-times-circle"></span> </h5>
							  <h5 class="modal-title" id="erroremail4">&nbsp;</h5>
							  <h5 class="modal-title" id="erroremail5"></h5>

						</div>
						
						  <div class="modal-footer">
							<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
							 </div>
						</div>
					  </div>
					</div>	

		<!-- Modal Error EMAIL-->
					<div class="modal fade" id="modal_ok" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
					  <div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
						
						<div class="text-center mt-5 mb-1">
						<div class="avatar avatar-xl">
						  <img src="assets/images/ok.png" alt="Avatar" class="avatar-img rounded-circle">
						</div>
						</div>						
						
						  <div class="text-center mt-5 mb-1">
							  <h4 class="modal-title" id="ok1">USUARIO REGISTRADO SATISFACTORIAMENTE</h5>
							  <h5 class="modal-title" id="ok2">&nbsp;</h5>
							  <h5 class="modal-title" id="ok3"><span class="far fa-times-circle"></span> </h5>
							  <h5 class="modal-title" id="ok4">&nbsp;</h5>
							  <h5 class="modal-title" id="ok5"></h5>

						</div>
						
						  <div class="modal-footer">
							<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
							 </div>
						</div>
					  </div>
					</div>	
					
	<!-- Modal Error PWD-->
					<div class="modal fade" id="modal_error_pwd1" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
					  <div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
						
						<div class="text-center mt-5 mb-1">
						<div class="avatar avatar-xl">
						  <img src="assets/images/error.png" alt="Avatar" class="avatar-img rounded-circle">
						</div>
						</div>						
						
						  <div class="text-center mt-5 mb-1">
							  <h4 class="modal-title" id="exampleModalLongTitle1">Error de Password </h5>
							  <h5 class="modal-title" id="exampleModalLongTitle5">&nbsp;</h5>
							  <h5 class="modal-title" id="exampleModalLongTitle2"><span class="far fa-times-circle"></span> </h5>
							  <h5 class="modal-title" id="exampleModalLongTitle3">&nbsp;</h5>
							  <h5 class="modal-title" id="exampleModalLongTitle4"></h5>
						</div>
						
						  <div class="modal-footer">
							<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
							 </div>
						</div>
					  </div>
					</div>				
					
				<?php } else {?>
				
			<?php	if (!$variables) {?>
               <div class="alert alert-dark text-center" role="alert">
			   <h3>Token inválido</h3>
				</div>
		<?php		} else 	if (!$encontrado) {?>
               <div class="alert alert-dark text-center" role="alert">
			   <h3>Token inválido</h3>
				</div>
			<?php	} else 	if ($diferencia_total>$app_tiempo_validez_confirmacion_password) {?>
               <div class="alert alert-dark text-center" role="alert">
			   <h3>Se ha sobrepasado el tiempo de respuesta para el token indicado</h3>
				</div>
		       <form method="get" action="app_forgot_password.php">
    
						<button id="btnsignup" name="signup" type="submit" class="btn btn-primary btn-block">Solicitar cambio contraseña</button>				
		
</form>		
				
			<?php	}		?>			
			   <?php } ?>
            </div>
			
			<div class="card-footer text-center text-black-50">Accede a la pantalla de Login <a href="app_login.php">Accede</a></div>
        </div>
    </div>


    <!-- jQuery -->
    <script src="assets/vendor/jquery.min.js"></script>

    <!-- Bootstrap -->
    <script src="assets/vendor/popper.min.js"></script>
    <script src="assets/vendor/bootstrap.min.js"></script>

    <!-- Perfect Scrollbar -->
    <script src="assets/vendor/perfect-scrollbar.min.js"></script>

    <!-- MDK -->
    <script src="assets/vendor/dom-factory.js"></script>
    <script src="assets/vendor/material-design-kit.js"></script>

    <!-- App JS -->
    <script src="assets/js/app.js"></script>

    <!-- Highlight.js -->
    <script src="assets/js/hljs.js"></script>

    <!-- App Settings (safe to remove) -->
    <script src="assets/js/app-settings.js"></script>
	
<script>

$('#modal_ok').on('hidden.bs.modal', function (e) {
var tmp1='<?php echo $u;?>';
var tmp2='<?php echo $token; ?>';
//window.location.replace("app_login.php?u="+tmp1+"&token="+tmp2);
window.location.replace("app_login.php");
})

				$(".reveal").on('click',function() {
    var $password = $(".pwd");
    if ($password.attr('type') === 'password') {
        $password.attr('type', 'text');
    } else {
        $password.attr('type', 'password');
    }
});	

				$(".reveal2").on('click',function() {
    var $password = $(".pwd2");
    if ($password.attr('type') === 'password') {
        $password.attr('type', 'text');
    } else {
        $password.attr('type', 'password');
    }
});	

$(document).ready(function(){


$(document).on('submit', '#form_log', function(){
	
	resultado=comprobar_pwd();
	if (resultado){
	document.getElementById("btnsignup").disabled = true;
    document.getElementById("loading").style.display = "block";
	var sign_up_form=$(this);
    var form_data=JSON.stringify(sign_up_form.serializeObject());
	
  $.ajax({
        url: "api/json/confirmation_forgot_password_json_app.php",
        type : "POST",
        contentType : 'application/json',
        data : form_data,
		
        success : function(result) {
			
			var cadena;
	        cadena="";
			
			cadena=cadena+"<span class='far fa-times-circle'></span> "+result.message+"<br>";
			cadena=cadena+""+"<br>";
		    document.getElementById("ok3").innerHTML =cadena;
		    document.getElementById("ok5").innerHTML = "<img width='20%' src='assets/images/logos_apps/logo.png'>";
			
			$('#modal_ok').modal();
				document.getElementById("btnsignup").disabled = false;
                document.getElementById("loading").style.display = "none";			
			
				//window.location.replace("guest-login.php?u="+result.message+"&token="+result.token);
        },
        error: function(response){
            // on error, tell the user sign up failed
			var obj = JSON.parse(response.responseText);
			var cadena;
	        cadena="";
			
			cadena=cadena+"<span class='far fa-times-circle'></span> "+obj.message+"<br>";
			cadena=cadena+""+"<br>";
		    document.getElementById("erroremail3").innerHTML =cadena;
		    document.getElementById("erroremail5").innerHTML = "<img width='20%' src='assets/images/logos_apps/logo.png'>";
			
			$('#modal_error_email').modal();
				document.getElementById("btnsignup").disabled = false;
                document.getElementById("loading").style.display = "none";
				
        }
    });
	}
	return false;
});



// remove any prompt messages
function clearResponse(){
    $('#response').html('');
}
 

// function to make form values to json format
$.fn.serializeObject = function(){
 
    var o = {};
    var a = this.serializeArray();
	
    $.each(a, function() {
		
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};


});

</script>




</body>

</html>