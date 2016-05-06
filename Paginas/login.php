<?php
	require_once("funciones.php");
	//Cargamos el idioma
	require_once("../".idioma());
	
	$conexion = conectarse(); 
	//Obtenemos las variables de el formulario de index.
	$usuario = $_POST['usuario'];
	$con = $_POST['con']; 
	
	//Comprobamos los datos de login del usuario
	$consulta = "SELECT * FROM usuarios WHERE ACTIVA = 'S' AND UPPER(NOMBRE) = UPPER('". $usuario ."') AND CLAVE = AES_ENCRYPT('".$con."',UPPER('".$usuario."'));";
	$result = $conexion->query($consulta);
	$tuplas = $result->num_rows;
	$res = $result->fetch_assoc();
	mysqli_close($conexion); //Cerramos conexion
	if($tuplas == 0) //Si no devuelve ningun resultado redirigimos a error de login 
	{			
		header("Location: ../paginas/error.php?e=".urlencode($texto["Login_1"]));
	}
	else //Si si que nos devuelve datos iniciamos sesion
	{						
		if (session_id() == '')
		{
			session_start();//Iniciamos sesion y creamos una variable de sesion con el nombre del usuario.
		} 
		$_SESSION["usuario"] = $res["NOMBRE"];
		$_SESSION["id_usuario"] = $res["ID_USUARIO"];
		mysqli_close($conexion); //Cerramos conexion
		
		if($res["TIPO"] == "G")
		{
			header("Location: ../paginas/administracion.php");
			$_SESSION["administrador"] = "si";
		}
		else
		{
			header("Location: ../paginas/portada.php");
		}
	}
?>