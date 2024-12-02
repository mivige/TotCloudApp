<?php
$validado='0';
    $stmt = $dbb->prepare('SELECT * FROM user    WHERE  token= ? and active=1 LIMIT 0,1');
    $dbb->set_charset("utf8");
	$stmt->bind_param('s',$_SESSION['app_user_token']);
    $stmt->execute();
    $result = $stmt->get_result();
	if ($row = $result->fetch_assoc()) {
		
		$fecha=$row['token_date'];
		$id_user=$row['id'];
		$firstname_user=$row['firstname'];
		$lastname_user=$row['lastname'];
		$lastname2_user=$row['lastname2'];
		$email_user=$row['email'];
        $es_admin=$row['admin'];
        $datetime1 = date_create($fecha);
        $datetime2 = new DateTime();
        $interval = date_diff($datetime1, $datetime2);
        $diferencia_min=$interval->format('%i');	
        $diferencia_horas=$interval->format('%h')*60;
        $diferencia_dias=$interval->format('%a')*24*60;		
		$diferencia_total=$diferencia_min+$diferencia_dias+$diferencia_horas;
		if ($diferencia_total>$app_tiempo_validez_token) {
			header("Location: app_login.php");
			exit;
		} else {
			
		//Falta Comprobar los estados para poner en las notificaciones
			
	      //Generate a random string.
          $token = openssl_random_pseudo_bytes(32);
          //Convert the binary data into hexadecimal representation.
          $token = bin2hex($token);
          // create the user
		  $fecha_token=date("Y-m-d H:i:s");
          $stmt = $dbb->prepare('update user  set  token= ?,token_date=? where token=? ');
		  $dbb->set_charset("utf8");
		  $stmt->bind_param("sss", $token, $fecha_token,$_SESSION['app_user_token']);
	      $stmt->execute();	
          $_SESSION['app_user_token']=$token;
		  $_SESSION['roles']=[];	

	
	  	  $stmt1 = $dbb->prepare("Call GetUserRoles(?)");
	  	  $dbb->set_charset("utf8");
	      $stmt1->bind_param('i', $id_user);
	      $stmt1->execute();
	      $result1 = $stmt1->get_result();
	      while ($row = $result1->fetch_assoc()) {
		    $_SESSION['roles'][] = $row['code'];
	      }
	      $stmt1->close();
         	  
		}
		
	} else {
			header("Location: app_login.php");
			exit;		
		
	}


	function tieneRol($rol) {
		return in_array($rol, $_SESSION['roles']);
	}
	
	
	function actualizarRolesSesion($userId, $mysqli) {
		// Preparar la consulta
		$stmt = $mysqli->prepare("
			SELECT r.code
			FROM u_role r
			JOIN u_user_x_role ur ON ur.role_id = r.id
			WHERE ur.user_id = ?
		");
		
		$stmt->bind_param('i', $userId);
		$stmt->execute();
		$result = $stmt->get_result();
		
		// Guardar los roles en la sesión
		$_SESSION['roles'] = [];
		while ($row = $result->fetch_assoc()) {
			$_SESSION['roles'][] = $row['code'];
		}
		
		$stmt->close();
	}


	?>