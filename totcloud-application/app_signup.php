<?php
include_once 'https_redirect.php';
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 1 Jul 2000 05:00:00 GMT"); // Fecha en el pasado
include_once 'api/config/database_app.php';
date_default_timezone_set('UTC');
session_start();
//Tiempo de validez del password en dias

include_once "api/config/variables.php";

?>




<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Registro</title>
<link rel="icon" type="image/png" sizes="32x32" href="assets/images/logo/favicon.png">
    <!-- Prevent the demo from appearing in search engines (REMOVE THIS) -->
    <meta name="robots" content="noindex">

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

    <link rel="stylesheet" href="api/iti/css/intlTelInput.css">

		

</head>

<body class="login">




    <div class="d-flex align-items-center" style="min-height: 100vh">
        <div class="col-sm-8 col-md-6 col-lg-4 mx-auto" style="min-width: 300px;">
            <div class="text-center mt-5 mb-1">
                 <img src="assets/images/logos_apps/logo.png"  width="35%" alt="<?php if (isset($app_titulo_principal)) {echo ($app_titulo_principal);} ?>" />
            </div>
            <div class="d-flex justify-content-center mb-5 navbar-light">
               <h3><?php if (isset($app_titulo_principal)) {echo ($app_titulo_principal);} ?></h3>
            </div>
            <div class="card navbar-shadow">
                <div class="card-header text-center">
                    <h4 class="card-title">Regístrate</h4>
                    <p class="card-subtitle">Crear una cuenta Nueva</p>
                </div>
				




                <div class="card-body">

	
 	<script>
	
	  function checkPassword(str)
      {
        var re = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/;
        return re.test(str);
      }
  
  
  function comprobar_mobile_phone(){
	  
	  var valido=iti.isValidNumber();
	  if (valido){return true;}else
		  {
			  var cadena;
	        cadena="";
			
			cadena=cadena+"<span class='far fa-times-circle'></span> Telefono Móvil Incorrecto<br>";
			cadena=cadena+""+"<br>";
		    document.getElementById("errorphone3").innerHTML =cadena;
		    document.getElementById("errorphone5").innerHTML = "<img width='20%' src='assets/images/logos_apps/logo.png'>";
			$('#modal_error_phone').modal();
			  return false;
		  }
	  
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
                    <form id="form_log" >
					<input type="hidden" id="mobile_completo" name="mobile_completo" value="" >
					<div class="was-validated">
                        <div class="form-group">
                            <label class="form-label" for="firstname">Nombre:</label>
                            <div class="input-group input-group-merge">
                                <input id="firstname" name="firstname" value="<?php if (isset($_POST['firstname'])) {echo($firstname);}?>" type="text" required="" class="form-control form-control-prepended" placeholder="Nombre">
								<div class="invalid-feedback">Por Favor introduce tu nombre.</div>
                                 <div class="valid-feedback">Parece un nombre correcto</div>
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <span class="far fa-user"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
						</div>
						
					<div class="was-validated">
                        <div class="form-group">
                            <label class="form-label" for="lastname">Primer Apellido:</label>
                            <div class="input-group input-group-merge">
                                <input id="lastname" name="lastname" value="<?php if (isset($_POST['lastname'])) {echo($lastname);}?>" type="text" required="" class="form-control form-control-prepended" placeholder="Primer Apellido">
								<div class="invalid-feedback">Por Favor introduce tu Primer Apellido.</div>
                                 <div class="valid-feedback">Parece apellido correcto</div>
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <span class="far fa-user"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
						</div>			

					<div class="was-validated">
                        <div class="form-group">
                            <label class="form-label" for="lastname2">Segundo Apellido:</label>
                            <div class="input-group input-group-merge">
                                <input id="lastname2" name="lastname2" value="<?php if (isset($_POST['lastname'])) {echo($lastname);}?>" type="text" required="" class="form-control form-control-prepended" placeholder="Segundo Apellido">
								<div class="invalid-feedback">Por Favor introduce tu Segundo Apellido.</div>
                                 <div class="valid-feedback">Parece apellido correcto</div>
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <span class="far fa-user"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
						</div>							
						
						

					<div class="was-validated">
                        <div class="form-group">
                            <label class="form-label" for="mobile_phone">Telefono Movil:</label>
                            <div class="input-group input-group-merge">
                                <input id="mobile_phone" name="mobile_phone" value="<?php if (isset($_POST['mobile_phone'])) {echo($mobile_phone);}?>" >
								<div id="invalid-feedback" class="invalid-feedback" style="display: block;"> Por favor Introduce el móvil</div>
                            </div>
                        </div>
						</div>
						
	
	

<script src="api/iti/js/intlTelInput.js"></script>
<script>
var input = document.querySelector("#mobile_phone"),
  output = document.querySelector("#invalid-feedback");
 
var iti = window.intlTelInput(input, {
  nationalMode: false,
  separateDialCode:true,
  autoPlaceholder: "polite",
  initialCountry:"es",
   onlyCountries: ["al", "ad", "at", "by", "be", "ba", "bg", "hr", "cz", "dk",
  "ee", "fo", "fi", "fr", "de", "gi", "gr", "va", "hu", "is", "ie", "it", "lv",
  "li", "lt", "lu", "mk", "mt", "md", "mc", "me", "nl", "no", "pl", "pt", "ro",
  "ru", "sm", "rs", "sk", "si", "es", "se", "ch", "ua", "gb"],
  utilsScript: "api/iti/js/utils.js?1562189064761" // just for formatting/placeholders etc
});

var handleChange = function() {
  var text = (iti.isValidNumber()) ? "<span style='width:100%;margin-top:.25rem;font-size:.75rem;color:#66bb6a'>Número móvil Correcto: " + iti.getNumber()+"</span>" : "<span style='width:100%;margin-top:.25rem;font-size:.75rem;color:#f44336'>Número móvil Incorrecto: " + iti.getNumber()+"</span>";
  var textNode = document.createTextNode(text);
  output.innerHTML = "";
  output.innerHTML = text;
};

// listen to "keyup", but also "change" to update when the user selects a country
input.addEventListener('change', handleChange);
input.addEventListener('keyup', handleChange);



</script>

			
						<div class="was-validated">
                        <div class="form-group">
                            <label class="form-label" for="email">Email:</label>
                            <div class="input-group input-group-merge">
                                <input id="email" name="email" value="<?php if (isset($_POST['email'])) {echo($email);}?>" type="email" required="" class="form-control form-control-prepended" placeholder="Tú dirección de correo">
								<div class="invalid-feedback">Por favor proporciona tu dirección de correo.</div>
                                 <div class="valid-feedback">Parece una dirección de correo válida!</div>								
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
                            <label class="form-label" for="password">Contraseña</label>
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
                            <label class="form-label" for="password">Validar Contraseña:</label>
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
						<div id="loading" style="display: none;" class="form-group text-center mb-0">
	                   <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                      <span class="sr-only">Loading...</span>
  
                       </div>
                     <p><h3>Enviando</h3></p>
                     </div>
                        <button type="submit" id="btnsignup" name="signup" value="Registrar" class="btn btn-primary btn-block mb-3">Registrate</button>
						
                        <div class="form-group text-center mb-0">
                            <div class="custom-control custom-checkbox">
                                <input id="terms" type="checkbox" name="terms" class="custom-control-input" d checked required="">
                                <label for="terms" class="custom-control-label text-black-70">Acepto <button data-toggle="modal" data-target="#exampleModalLong" type="button" class="btn btn-link btn-rounded btn-sm"> las Condiciones de Uso</button></label> 
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

					
					
<!-- Modal Error MOBIL-->
					<div class="modal fade" id="modal_error_phone" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
					  <div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
						
						<div class="text-center mt-5 mb-1">
						<div class="avatar avatar-xl">
						  <img src="assets/images/error.png" alt="Avatar" class="avatar-img rounded-circle">
						</div>
						</div>						
						
						  <div class="text-center mt-5 mb-1">
							  <h4 class="modal-title" id="errorphone1">ERROR EN EL MÓVIL</h5>
							  <h5 class="modal-title" id="errorphone2">&nbsp;</h5>
							  <h5 class="modal-title" id="errorphone3"><span class="far fa-times-circle"></span> </h5>
							  <h5 class="modal-title" id="errorphone4">&nbsp;</h5>
							  <h5 class="modal-title" id="errorphone5"></h5>

						</div>
						
						  <div class="modal-footer">
							<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
							 </div>
						</div>
					  </div>
					</div>						


					<!-- Modal Agree-->
					<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
					  <div class="modal-dialog modal-xl" role="document">
						<div class="modal-content">
						  <div class="modal-header">
							<h5 class="modal-title" id="exampleModalLongTitle">Tearms of Use</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							  <span aria-hidden="true">&times;</span>
							</button>
						  </div>
						  <div class="modal-body">
							
							<?php //include 'terms_of_use.html';?>
							
					
							
							
						  </div>
						  <div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
						   
						  </div>
						</div>
					  </div>
					</div>						
						
						
                    </form>
					
				

<!-- Modal Error PWD-->
		<!-- Modal Error EMAIL-->
					<div class="modal fade" id="modal_error_login" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
					  <div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
						
						<div class="text-center mt-5 mb-1">
						<div class="avatar avatar-xl">
						  <img src="assets/images/error.png" alt="Avatar" class="avatar-img rounded-circle">
						</div>
						</div>						
						
						  <div class="text-center mt-5 mb-1">
							  <h4 class="modal-title" id="modal_error_login_1">ERROR DE VALIDACIÓN</h5>
							  <h5 class="modal-title" id="modal_error_login_2">&nbsp;</h5>
							  <h5 class="modal-title" id="modal_error_login_3"><span class="far fa-times-circle"></span> </h5>
							  <h5 class="modal-title" id="modal_error_login_4">&nbsp;</h5>
							  <h5 class="modal-title" id="modal_error_login_5"></h5>

						</div>
						
						  <div class="modal-footer">
							<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
							 </div>
						</div>
					  </div>
					</div>	

<!-- Modal Error PWD-->
		<!-- Modal Error EMAIL-->
					<div class="modal fade" id="modal_error_pwd" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
					  <div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
						
						<div class="text-center mt-5 mb-1">
						<div class="avatar avatar-xl">
						  <img src="assets/images/error.png" alt="Avatar" class="avatar-img rounded-circle">
						</div>
						</div>						
						
						  <div class="text-center mt-5 mb-1">
							  <h4 class="modal-title" id="modal_error_pwd_1">ERROR DE VALIDACIÓN</h5>
							  <h5 class="modal-title" id="modal_error_pwd_2">&nbsp;</h5>
							  <h5 class="modal-title" id="modal_error_pwd_3"><span class="far fa-times-circle"></span> </h5>
							  <h5 class="modal-title" id="modal_error_pwd_4">&nbsp;</h5>
							  <h5 class="modal-title" id="modal_error_pwd_5"></h5>

						</div>
						
						  <div class="modal-footer">
							<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
							 </div>
						</div>
					  </div>
					</div>	

				
					
                </div>
                <div class="card-footer text-center text-black-50">Ya estás registrado? <a href="app_login.php">Accede</a></div>
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
	
    document.getElementById("mobile_completo").value=iti.getNumber();
	resultado=false;
	resultado1=false;
	resultado1=comprobar_mobile_phone();
	if (resultado1){
	  resultado=comprobar_pwd();
	} else {alert("invalido");}
    if (resultado){
    // get form data
	document.getElementById("btnsignup").disabled = true;
    document.getElementById("loading").style.display = "block";
    var sign_up_form=$(this);
    var form_data=JSON.stringify(sign_up_form.serializeObject());
 
    // submit form data to api
    $.ajax({
        url: "api/json/create_user_json_app.php",
        type : "POST",
        contentType : 'application/json',
        data : form_data,
        success : function(response) {
			
			var cadena_token = response.cadena_token;

						
			
			//var obj2 = JSON.parse(response);
            // if response is a success, tell the user it was a successful sign up & empty the input boxes
            //$('#response').html("<div class='alert alert-success'>Registro satisfactorio.</div>");
            //sign_up_form.find('input').val('');
			window.location.replace("app_confirmation_validate.php?"+cadena_token);
        },
        error: function(response){
            // on error, tell the user sign up failed
			var obj = JSON.parse(response.responseText);
			//console.log(obj.message);
            //$('#response').html("<div class='alert alert-danger'>No se ha podido crear el usuario. "+obj.message+". Intentelo de nuevo.</div>");
			//$('#alert_email_existe').show();
			
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