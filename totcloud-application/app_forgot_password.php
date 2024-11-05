<?php
include_once 'https_redirect.php';
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 1 Jul 2000 05:00:00 GMT"); // Fecha en el pasado
include_once 'api/config/database_app.php';
date_default_timezone_set('UTC');
session_start();

include_once "api/config/variables.php";
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Registro</title>

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
                <!-- Brand -->
                
                    <h3><?php if (isset($app_titulo_principal)) {echo ($app_titulo_principal);} ?></h3>
               
            </div>
            <div class="card navbar-shadow">
                <div class="card-header text-center">
                    <h4 class="card-title">¿Has olvidado la contraseña?</h4>
                    <p class="card-subtitle">Recuperar tu password</p>
                </div>
                <div class="card-body">
  
               
                   <form id="form_log" >
					
					<div class="was-validated">
                        <div class="form-group">
                            <label class="form-label" for="email">Introduce el EMAIL:</label>
                            <div class="input-group input-group-merge">
                                <input id="email" name="email" value="<?php if (isset($_POST['email'])) {echo($email);}?>" type="email" required="" class="form-control form-control-prepended" placeholder="Introduce tu Email">
								<div class="invalid-feedback">Por Favor introduce tu email.</div>
                                 <div class="valid-feedback">Parece un email correcto</div>
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <span class="far fa-user"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
						</div>
						
	        <div id="loading" style="display: none;" class="form-group text-center mb-0">
	                   <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                      <span class="sr-only">Loading...</span>
  
                       </div>
                     <p><h3>Enviando</h3></p>
                     </div>
					 
				 
					 
					 
                        <button type="submit" id="btnsignup" name="signup" value="Registrar" class="btn btn-primary btn-block mb-3">Solicita tu contraseña</button>
                    </form>
                </div>
                <div class="card-footer text-center text-black-50">¿Te acuerdas de tu password? <a href="app_login.php">Login</a></div>
            </div>
        </div>
		
	    </div>

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
							  <h4 class="modal-title" id="erroremail1">ERROR AL SOLICITAR CONTRASEÑA</h5>
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


$(document).ready(function(){


$(document).on('submit', '#form_log', function(){

    
	document.getElementById("btnsignup").disabled = true;
    document.getElementById("loading").style.display = "block";

    var sign_up_form=$(this);
    var form_data=JSON.stringify(sign_up_form.serializeObject());
 
    // submit form data to api
    $.ajax({
        url: "api/json/forgot_password_json_app.php",
        type : "POST",
        contentType : 'application/json',
        data : form_data,
        success : function(response) {
			
			//var cadena_token = response.cadena_token;
			var cadena_token = response.cadena_token;
							document.getElementById("btnsignup").disabled = false;
                 document.getElementById("loading").style.display = "none";
			window.location.replace("app_confirmation_forgot_password.php?"+cadena_token);
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

    return false;
});

// remove any prompt messages
function clearResponse(){
    $('#response').html('');
}
 
// showLoginPage() will be here
 
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