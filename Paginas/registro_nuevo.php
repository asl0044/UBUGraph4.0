<?php	
	require_once("funciones.php");
	require_once("PHPMailerAutoload.php");
	require_once("confg.php");
	
	//Cargamos el idioma
	require_once("../".idioma());

	echo header('Content-Type: text/html; charset=utf-8'); 

	$fallo = false;

	//Comprobamos que hemos recibido los datos del formulario
	if(isset($_POST['usuario']) && isset($_POST['con1']) && isset($_POST['con2']) && isset($_POST['correo']) && isset($_POST['tipo']))
	{
		$usuario = $_POST['usuario'];
		$con1 = $_POST['con1'];
		$con2 = $_POST['con2'];
		$correo = $_POST['correo'];
		$tipo = $_POST['tipo'];
		
		if($_POST['con2'] != $con1)
		{
			$fallo = true;
		}
	}
	else 
	{
		$fallo = true;
	}

	//Si los hemos recibido lo damos de alta.
	if(!$fallo)
	{
		$conexion = conectarse();
		
		//Comprobamos que no existen usuarios ni correos iguales dados de alta previamente.
		$consulta = "SELECT * FROM usuarios WHERE UPPER(NOMBRE) = UPPER('{$usuario}') OR UPPER(CORREO) = UPPER('{$correo}');";
		$res = $conexion->query($consulta);
		$tuplas = $res->num_rows;
		if($tuplas == 0)
		{
			//Damos de alta el usuario
			$conexion->query("INSERT INTO usuarios (ID_USUARIO, NOMBRE, CLAVE, TIPO, CORREO, ACTIVA) VALUES (0,'{$usuario}',AES_ENCRYPT('{$con1}',UPPER('{$usuario}')),'{$tipo}','{$correo}', 'N');"); 
			
			//Creamos la instancia de la clase PHPMailer y configuramos la cuenta
			$phpmailer=new PHPMailer();
				
			$phpmailer->IsSMTP();
			//Datos de la cuenta que usaremos para realizar el envio del correo
			$phpmailer->Host = $cfg["smtp"]; //Direccion del smtp de salida
			$phpmailer->SMTPAuth = true;
			$phpmailer->Port = $cfg["smtpPort"]; //Puerto
			$phpmailer->Username = $cfg["mailUser"]; //Cuenta que usaremos
			$phpmailer->Password = $cfg["mailPass"]; //Contraseña de la cuenta
			
			$phpmailer->SetFrom($cfg["mailUser"], 'Administrador'); //Campo FROM del correo
			
			$phpmailer->CharSet = 'UTF-8';
			
			$phpmailer->Subject = $texto["Registro_nuevo_1"]; //Asunto
			
			//Cuerpo del mensaje
			$phpmailer->MsgHTML("<HTML>
					<HEAD>
						<TITLE>".$texto["Registro_nuevo_2"]."</TITLE>
						<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
					</HEAD>
					<BODY>
						<P>".$texto["Registro_nuevo_3"]."</P>
						<P>".$texto["Registro_nuevo_4"].".</P>
						<P>".$texto["Registro_nuevo_5"]."<BR>
							".$texto["Registro_nuevo_6"]." ".$usuario.". ".$texto["Registro_nuevo_7"]." ".$con1."</P>
						<P>".$texto["Registro_nuevo_8"]."<BR>http://".$cfg["URL"]."/paginas/activar.php?r=".urlencode(Encrypter::encrypt($usuario))."</P>
						<P>".$texto["Registro_nuevo_9"]."</P>
						<P>".$texto["Registro_nuevo_10"]."</P>
					</BODY>
				</HTML>");
			
			//Direccion y nombre del usuario
			$phpmailer->AddAddress($correo, $usuario);			
			
			//Cuerpo html alternativo
			$phpmailer->Body="<HTML>
					<HEAD>
						<TITLE>".$texto["Registro_nuevo_2"]."</TITLE>
						<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
					</HEAD>
					<BODY>
						<P>".$texto["Registro_nuevo_3"]."</P>
						<P>".$texto["Registro_nuevo_4"].".</P>
						<P>".$texto["Registro_nuevo_5"]."<BR>
							".$texto["Registro_nuevo_6"]." ".$usuario.". ".$texto["Registro_nuevo_7"]." ".$con1."</P>
						<P>".$texto["Registro_nuevo_8"]."<BR>http://".$cfg["URL"]."/paginas/activar.php?r=".urlencode(Encrypter::encrypt($usuario))."</P>
						<P>".$texto["Registro_nuevo_9"]."</P>
						<P>".$texto["Registro_nuevo_10"]."</P>
					</BODY>
				</HTML>";
				
			//Cuerpo alternativo en texto plano
			$phpmailer->AltBody=$texto["Registro_nuevo_2"]."
						\n".$texto["Registro_nuevo_3"]."
						\n".$texto["Registro_nuevo_4"]."
						\n".$texto["Registro_nuevo_5"]."
						\n".$texto["Registro_nuevo_6"]." ".$usuario.". ".$texto["Registro_nuevo_7"]." ".$con1."
						\n".$texto["Registro_nuevo_8"]."
						\nhttp://".$cfg["URL"]."/paginas/activar.php?r=".urlencode(Encrypter::encrypt($usuario))."
						\n".$texto["Registro_nuevo_9"]."
						\n".$texto["Registro_nuevo_10"];
			
			//enviamos el correo
			$exito = $phpmailer->Send();
			
			//si se ha enviado correctamente mostramos una confirmacion
			if($exito)
			{
				header("Location: mensaje.php?m=".urlencode($texto["Registro_nuevo_11"]));
			}
			//O un aviso en caso contrario
			else
			{
				header("Location: error.php?e=".urlencode($texto["Registro_nuevo_12"]));
			}
		}
		//Si el usuario o el correo estan dados de alta ya, mostramos un error
		else
		{
			$fallo = true;
		}
		mysqli_close($conexion);
	}
	//Si nos faltan datos mostramos un error
	else
	{
		header("Location: error.php?e=".urlencode($texto["Registro_nuevo_14"]));
	}
	
	//Error mostrado al tener datos repetidos.
	if($fallo)
	{
		header("Location: error.php?e=".urlencode($texto["Registro_nuevo_13"]));
	}	
?>