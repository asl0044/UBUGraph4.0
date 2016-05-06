<?php
	require_once("funciones.php");
	require_once("PHPMailerAutoload.php");
	require_once("confg.php");
	//Cargamos los idiomas
	require_once("../".idioma());

	echo header('Content-Type: text/html; charset=utf-8');
	
	$fallo = false;

	//Comprobamos que hemos recibido el correo.
	if(isset($_POST['correo']))
	{
		$correo = $_POST['correo'];
	}
	else 
	{
		$fallo = true;
	}

	//Si lo hemos recibido realizamos la recuperacion
	if(!$fallo)
	{
		//Obtenemos los datos de la cuenta de la BD
		$conexion = conectarse();
		$consulta = "SELECT NOMBRE, CORREO, AES_DECRYPT(CLAVE,UPPER(NOMBRE)) PASS FROM usuarios WHERE TIPO <> 'I' AND UPPER(CORREO) = UPPER('{$correo}');";
		$res = $conexion->query($consulta);
		$tuplas = $res->num_rows;
		$reg = $res->fetch_assoc();
		mysqli_close($conexion);
		
		//Si el correo existe en la BD para una cuenta activa enviamos los datos del usuario
		if($tuplas != 0)
		{						
		//Creamos la instancia de la clase PHPMailer y configuramos la cuenta
			$phpmailer=new PHPMailer();
				
			$phpmailer->IsSMTP();
			//datos de la cuenta que usaremos para realizar el envio del correo
			$phpmailer->Host = $cfg["smtp"]; //Direccion del smtp de salida
			$phpmailer->SMTPAuth = true;
			$phpmailer->Port = $cfg["smtpPort"]; //Puerto
			$phpmailer->Username = $cfg["mailUser"]; //Cuenta que usaremos
			$phpmailer->Password = $cfg["mailPass"]; //Contraseña de la cuenta
			
			$phpmailer->SetFrom('UBUGraph@gmail.com', 'Administrador'); //Campo FROM del correo
			
			$phpmailer->CharSet = 'UTF-8';
			
			$phpmailer->Subject = $texto["Recuperando_1"];//Asunto
			
			//Cuerpo del mensaje
			$phpmailer->MsgHTML("<HTML>
					<HEAD>
						<TITLE>".$texto["Recuperando_2"]."</TITLE>
						<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
					</HEAD>
					<BODY>
						<P>".$texto["Recuperando_3"]."<BR>".$texto["Recuperando_4"]." ".$reg["NOMBRE"].". ".$texto["Recuperando_5"]." ".$reg["PASS"]."</P>
					</BODY>
				</HTML>");
			
			//Direccion y nombre del usuario
			$phpmailer->AddAddress($reg["CORREO"], $reg["NOMBRE"]);
			
			//Cuerpo html alternativo
			$phpmailer->Body="<HTML>
					<HEAD>
						<TITLE>".$texto["Recuperando_2"]."</TITLE>
						<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
					</HEAD>
					<BODY>
						<P>".$texto["Recuperando_3"]."<BR>".$texto["Recuperando_4"]." ".$reg["NOMBRE"].". ".$texto["Recuperando_5"]." ".$reg["PASS"]."</P>
					</BODY>
				</HTML>";
			
			//Cuerpo alternativo en texto plano
			$phpmailer->AltBody=$texto["Recuperando_2"]."
						\n".$texto["Recuperando_3"]."<BR>".$texto["Recuperando_4"]." ".$reg["NOMBRE"].". ".$texto["Recuperando_5"]." ".$reg["PASS"];
			
			//enviamos el correo
			$exito = $phpmailer->Send();
			
			//si se ha enviado correctamente mostramos una confirmacion
			if($exito)
			{
				header("Location: mensaje.php?m=".urlencode($texto["Recuperando_6"]));
			}
			//O un aviso en caso contrario
			else
			{
				header("Location: error.php?e=".urlencode($texto["Recuperando_7"]));
			}
		}
		//Si el correo no esta dado de alta, mostramos un error
		else
		{
			$fallo = true;
		}
		
	}
	//Si nos faltan datos mostramos un error
	else
	{
		header("Location: error.php?e=".urlencode($texto["Recuperando_8"]));
	}
	
	//Error mostrado al no estar dado de alta.
	if($fallo)
	{
		header("Location: error.php?e=".urlencode($texto["Recuperando_9"]));
	}	
?>