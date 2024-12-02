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
$diferencia_total="0";
//Tiempo de duracion del token en minutos



include_once "api/config/variables.php";






if (isset($_GET['u']) && !empty($_GET['u'])&& isset($_GET['token']) && !empty($_GET['token'])) {
  $variables=true;
  $u=$_GET['u'];
  $token=$_GET['token'];
}	


if ($variables){
    $stmt = $dbb->prepare('SELECT * FROM user    WHERE id= ? and token= ? LIMIT 0,1');
    $dbb->set_charset("utf8");
	$stmt->bind_param('ss', $u,$token);
    $stmt->execute();
    $result = $stmt->get_result();
	if ($row = $result->fetch_assoc()) {
		$encontrado=true;
		$fecha=$row['token_date'];
		
        $datetime1 = date_create($fecha);
        $datetime2 = new DateTime();
        $interval = date_diff($datetime1, $datetime2);
        $diferencia_min=$interval->format('%i');	
        $diferencia_horas=$interval->format('%h')*60;
        $diferencia_dias=$interval->format('%a')*24*60;		
		$diferencia_total=$diferencia_min+$diferencia_dias+$diferencia_horas;
		
} else {$encontrado=false;}
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Registration Confirmation</title>

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
                     <img src="assets/images/logos_apps/logo.png"  width="20%" alt="<?php if (isset($app_titulo_principal)) {echo ($app_titulo_principal);} ?>" />
            </div>
			
			
			
			
            <div class="d-flex justify-content-center mb-5 navbar-light">
                   <h3> <?php if (isset($app_titulo_principal)) {echo ($app_titulo_principal);} ?></h3>
            </div>
            <div class="card navbar-shadow">
                <div class="card-header text-center">
                    <h4 class="card-title">Registration Validation</h4>
                    <p class="card-subtitle">Two-factor validation of your registration</p>
                </div>
				
				
				
                <div class="card-body">
				
				<?php if (($variables) && ($encontrado) && ($diferencia_total<$app_tiempo_validez_token)) { ?>
				
                    <div class="alert alert-light border-1 border-left-3 border-left-primary d-flex" role="alert">
                        <i class="material-icons text-success mr-3">check_circle</i>
                        <div class="text-body">An email with Activation Code 1 for your account has been sent, and an SMS with Activation Code 2 for your account.<br> Please enter the received codes in the fields below to validate your account.</div>
                    </div>

                    <form id="form_log" >

                        <div class="was-validated">
						<div class="form-group">
                            <label class="form-label" for="codigo_1">Code 1 received in the email:</label>
                            <div class="input-group input-group-merge">
						
                                <input id="codigo_1" name="codigo_1" type="text" required="" class="form-control form-control-prepended" placeholder="Please provide your Code 1">
    								<div class="invalid-feedback">Please provide your Code 1.</div>
                                    <div class="valid-feedback">The code appears to be in the correct format</div>	                            
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
                            <label class="form-label" for="codigo_2">Code 2 received on the mobile:</label>
                            <div class="input-group input-group-merge">
                                <input id="codigo_2" name="codigo_2" type="text" required="" class="form-control form-control-prepended" placeholder="Please provide your Code 2">
    								<div class="invalid-feedback">Please provide your Code 2.</div>
                                 <div class="valid-feedback">The code appears to be in the correct format</div>	                                 
								<div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <span class="far fa-envelope"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
						</div>						
						<input type="hidden" id="token" name="token" class="form-control form-control-prepended" value="<?php if (isset($token)) {echo($token);}?>" >
					     <input type="hidden" id="u" name="u" class="form-control form-control-prepended" value="<?php if (isset($u)) {echo($u);}?>" >
						
					<div id="loading" style="display: none;" class="form-group text-center mb-0">
	                   <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                      <span class="sr-only">Loading...</span>
  
                       </div>
                     <p><h3>Sending</h3></p>
                     </div>					
						<button id="btnsignup" name="signup"type="submit" class="btn btn-primary btn-block">Validate User</button>
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
							  <h4 class="modal-title" id="erroremail1">REGISTRATION ERROR</h5>
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
							  <h4 class="modal-title" id="ok1">USER SUCCESSFULLY REGISTERED</h5>
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
					
				<?php } else {?>
				
			<?php	if (!$variables) {?>
               <div class="alert alert-dark text-center" role="alert">
			   <h3>Invalid token</h3>
				</div>
		<?php		} else 	if (!$encontrado) {?>
               <div class="alert alert-dark text-center" role="alert">
			   <h3>Invalid token</h3>
				</div>
			<?php	} else 	if ($diferencia_total>$app_tiempo_validez_token) {?>
               <div class="alert alert-dark text-center" role="alert">
			   <h3>The response time for the provided token has been exceeded<?php	 echo $app_tiempo_validez_token; ?></h3>
				</div>
			<?php	}		?>			
			   <?php } ?>
            </div>
			
			<div class="card-footer text-center text-black-50">Access the Login screen <a href="app_login.php">Access the Login screen</a></div>
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

$(document).ready(function(){


$(document).on('submit', '#form_log', function(){
		document.getElementById("btnsignup").disabled = true;
    document.getElementById("loading").style.display = "block";
	var sign_up_form=$(this);
    var form_data=JSON.stringify(sign_up_form.serializeObject());
	
  $.ajax({
        url: "api/json/validate_user_json_app.php",
        type : "POST",
        contentType : 'application/json',
        data : form_data,
		
        success : function(result) {
			
			
			//alert(cadena_token);
            // if response is a success, tell the user it was a successful sign up & empty the input boxes
            //$('#response').html("<div class='alert alert-success'>Registro satisfactorio.</div>");
            //sign_up_form.find('input').val('');
			
			//var obj = JSON.parse(response.responseText);
			//console.log(obj.message);
            //$('#response').html("<div class='alert alert-danger'>No se ha podido crear el usuario. "+obj.message+". Intentelo de nuevo.</div>");
			//$('#alert_email_existe').show();
			
			
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