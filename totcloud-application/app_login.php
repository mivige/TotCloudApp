<?php
include_once 'https_redirect.php';
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 1 Jul 2000 05:00:00 GMT"); // Fecha en el pasado
include_once 'api/config/database_app.php';
date_default_timezone_set('UTC');
session_start();
include_once "api/config/variables.php";
?>


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
        
		
		
		
		
		
		if(form_log.password.value != "" && form_log.password.value.length >5) 
		{

		} 
		else
		{
		cadena=cadena+"<span class='far fa-times-circle'></span> The password does not have 6 or more characters"+"<br>";
		}
		
		
		var pwd1=/^(?=.*[A-Z])/;
		if(form_log.password.value != "" &&  pwd1.test(form_log.password.value) == false) 
		{
		cadena=cadena+"<span class='far fa-times-circle'></span> The password must contain at least one uppercase letter"+"<br>";
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
		cadena=cadena+"<span class='far fa-times-circle'></span> The password must contain at least one lowercase letter"+"<br>";
		}		

        var pwd3=/^(?=.*[0-9])/;
		if(form_log.password.value != "" && pwd3.test(form_log.password.value) == true) 
		{
		} 
		else
		{
		cadena=cadena+"<span class='far fa-times-circle'></span> The password must contain at least one number"+"<br>";
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
		   cadena=cadena+"<span class='far fa-times-circle'></span> The password must contain at least one special character"+"<br>";
		}	
		cadena=cadena+""+"<br>";
		document.getElementById("modal_error_login_2").innerHTML =cadena;
		document.getElementById("modal_error_login_4").innerHTML = "<img width='20%' src='assets/images/logos_apps/logo.png'>";
        form_log.password.focus();
		
		
		
        $('#modal_error_login').modal();		
		
		
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
		return true;
		} 
		else
		{
			
		   cadena=cadena+"<span class='far fa-times-circle'></span> The password must contain at least one special character"+"<br>";
		
		cadena=cadena+""+"<br>";
		document.getElementById("modal_error_login_2").innerHTML =cadena;
		document.getElementById("modal_error_login_4").innerHTML = "<img width='20%' src='assets/images/logos_apps/logo.png'>";
		$('#modal_error_login').modal();
        form_log.password.focus();		
		return false;
		}		
		
     
      
    }	
	}
	
	
	
	
	</script>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Last-Modified" content="0">
    <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
    <meta http-equiv="Pragma" content="no-cache">	
	
	
    <title>Login</title>
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







</head>





<body class="login">


    <div class="d-flex align-items-center" style="min-height: 100vh">
        <div class="col-sm-8 col-md-6 col-lg-4 mx-auto" style="min-width: 300px;">
            <div class="text-center mt-5 mb-1">
             
                    <img src="assets/images/logos_apps/logo.png" width="35%" alt="<?php if (isset($app_titulo_secundario)) {echo ($app_titulo_secundario);} ?>" />
               
            </div>
            <div class="d-flex justify-content-center mb-5 navbar-light">
               <h5><?php if (isset($app_titulo_secundario)) {echo ($app_titulo_secundario);} ?></h5>
        </div>
			
			
            <div class="card navbar-shadow">
                <div class="card-header text-center">
                    <h4 class="card-title">Login</h4>
                    <p class="card-subtitle">Access your account</p>
                </div>
                <div class="card-body">
				
			<?php	
			if (isset($_POST['signup'])){
				if (isset($es_ok))
                 {
			      if ($es_ok==false){ ?>

				<div class="text-center mt-5 mb-1">
				<div class="avatar avatar-xl">
				<img src="assets/images/error.png" alt="Avatar" class="avatar-img rounded-circle">
				</div>
				</div>	
			
		        <div class="card-header text-center">
                    <h4 class="card-title">AUTHENTICATION ERROR
	            <?php if ($existe_email){ ?>
					<br>INCORRECT PASSWORD
				<?php } else { ?>
					<br>USER <?php echo($email);?><br>DOESN`T EXIST
				<?php } ?>					
				   </h4>
                    <p class="card-subtitle"></p>
                </div>	
			<?php
				
			}

}
			}
?>				

<!--
                    <a href="student-dashboard.html" class="btn btn-light btn-block">
                        <span class="fab fa-google mr-2"></span>
                        Continue with Google
                    </a>

                    <div class="page-separator">
                        <div class="page-separator__text">or</div>
                    </div>
-->
                     <form id="form_log"  >
					<div class="was-validated">
                        <div class="form-group">
                            <label class="form-label" for="email">Your email address:</label>
                            <div class="input-group input-group-merge">
                                <input id="email" name="email" type="email" required class="form-control form-control-prepended" placeholder="Your mail">
								<div class="invalid-feedback">Please enter an email.</div>
                                 <div class="valid-feedback">It looks like a correct email!</div>
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
                            <label class="form-label" for="password">Your password:</label>
                            <div class="input-group input-group-merge">
                                <input id="password" name="password" type="password" required="" class="form-control form-control-appended pwd" placeholder="Your password">
							
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                      <span style="cursor:pointer" class="material-icons reveal">remove_red_eye</span>
										 
                                    </div>
                                </div>
									 <div class="invalid-feedback">Please enter a password.</div>
                                 <div class="valid-feedback">It seems like a correct password!</div>
	
                            </div>
                        </div>
						</div>
						
					
											<div id="loading" style="display: none;" class="form-group text-center mb-0">
	                   <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                      <span class="sr-only">Loading...</span>
  
                       </div>
                     <p><h3>Sending</h3></p>
                     </div>	
						
						
                        <div class="form-group ">
                            <button id="btnsignup" name="signup" type="submit"  class="btn btn-primary btn-block">Login</button>
                        </div>
                    					
                        <div class="text-center">
                            <a href="app_forgot_password.php" class="text-black-70" style="text-decoration: underline;">Have you forgotten your password?</a>
                        </div>
                    </form>
                </div>
				<?php if (true) { ?>
                <div class="card-footer text-center text-black-50">
                Aren't you a user yet? <a href="app_signup.php">Sign up</a>
                </div>
				<?php }?>
            </div>
        </div>
    </div>
	
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
	
      <div class="modal-header">
	  
	  	  
        <h5 class="text-center modal-title" id="exampleModalLabel">Introduce el código de la SALA</h5>
		
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>

	  <form method="get" action="video_free.php">
      <div class="modal-body">
        
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Sala:</label>
            <input type="text" required="" class="form-control" name="sala" id="sala">
          </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Entrar</button>
      </div>
	  </form>
    </div>
  </div>
</div>

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
							  <h4 class="modal-title" id="modal_error_login_1">VALIDATION ERROR</h5>
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
							  <h4 class="modal-title" id="modal_error_pwd_1">VALIDATION ERROR</h5>
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

$('#modal_error_pwd').on('hidden.bs.modal', function (e) {

window.location.replace("app_forgot_password.php");
})



				$(".reveal").on('click',function() {
    var $password = $(".pwd");
    if ($password.attr('type') === 'password') {
        $password.attr('type', 'text');
    } else {
        $password.attr('type', 'password');
    }
});	

$(document).ready(function(){
    // show sign up / registration form
	

	
	
	
    $(document).on('click', '#sign_up', function(){


       
    });

$(document).on('submit', '#form_log', function(){
 	 
	
	document.getElementById("btnsignup").disabled = true;
    document.getElementById("loading").style.display = "block";
    var sign_up_form=$(this);
    var form_data=JSON.stringify(sign_up_form.serializeObject());
$.ajax({
        url: "api/json/login_user_json_app.php",
        type : "POST",
        contentType : 'application/json',
        data : form_data,
        success : function(response) {
       
            // if response is a success, tell the user it was a successful sign up & empty the input boxes
            //$('#response').html("<div class='alert alert-success'>Successful sign up. Please login.</div>");
            //sign_up_form.find('input').val('');
			
			
			var accion=response.mod_pwd;
			//alert(response.mod_pwd);
			//alert(response.message);
			document.getElementById("btnsignup").disabled = false;
            document.getElementById("loading").style.display = "none";
			if ((accion=="1") || (accion=="2")){ 
			
				cadena="";
			cadena=cadena+"<span class='far fa-times-circle'></span> "+response.message+"<br>";
			cadena=cadena+""+"<br>";
			document.getElementById("modal_error_pwd_1").innerHTML ='EXPIRED PASSWORD';
		    document.getElementById("modal_error_pwd_3").innerHTML =cadena;
		    document.getElementById("modal_error_pwd_5").innerHTML = "<img width='20%' src='assets/images/logos_app/logo.png'>";
			document.getElementById("btnsignup").disabled = false;
            document.getElementById("loading").style.display = "none";	
			$('#modal_error_pwd').modal();
	
			} 
			if (accion=="0"){ 
			
            sessionStorage.setItem('app_user_token', response.token);
			window.location.replace("index.php");
			} 
					
        },
        error: function(response){
            // on error, tell the user sign up failed
			//var obj = JSON.parse(response.responseText);
		    
            var obj = JSON.parse(response.responseText);			
			var cadena;
	        
			cadena="";
			cadena=cadena+"<span class='far fa-times-circle'></span> "+obj.message+"<br>";
			cadena=cadena+""+"<br>";
		    document.getElementById("modal_error_login_3").innerHTML =cadena;
		    document.getElementById("modal_error_login_5").innerHTML = "<img width='20%' src='assets/images/logos_apps/logo.png'>";
			$('#modal_error_login').modal();
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