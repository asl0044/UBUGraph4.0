<?php	
	//Comprobamos que el usuario esta logueado.
	session_start();
	if (!isset($_SESSION["usuario"]))
	{ 
		header("Location: /");
	}
	require_once("funciones.php");
	//Cargamos el idioma
	require_once("../".idioma());
	echo header('Content-Type: text/html; charset=utf-8');
	
	//Comprobamos que hemos recibido un nombre para el grupo
	if(isset($_POST['nombre']))
	{
		$nombre = $_POST['nombre'];
	}
	//Si no hay nombre mostramos un error
	else 
	{
		header("Location: error.php?e=".urlencode($texto["Crear_grupo_1"]));
	}
		
	$conexion = conectarse();
	
	//Em caso contrario creamos el nuevo grupo
	$consulta = "INSERT INTO grupos(ID_GRUPO, ID_TUTOR, NOMBRE, CODIGO) VALUES(0, {$_SESSION["id_usuario"]}, '{$nombre}', '');";
	$result = $conexion->query($consulta);
	
	$ultimo = $conexion->insert_id;
	
	$consulta = "UPDATE GRUPOS SET CODIGO = CONCAT(ID_GRUPO, '_', ID_TUTOR) WHERE ID_GRUPO = {$ultimo}";
	$result = $conexion->query($consulta);
	
	mysqli_close($conexion);
	
	header("Location: ./tutoria.php");
?>