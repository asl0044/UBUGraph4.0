<?php
	require_once("funciones.php");
	//Cargamos el idioma
	require_once("../".idioma());
	
	$conexion = conectarse(); //nos conectamos a la base de datos
	$usuario = Encrypter::decrypt($_GET["r"]); //Guardamos en la variable usuario la variable de la url el nombre del usuario
	
	//Comprobamos que el codigo de verificacion coincide para el usuario y que si existe esta pendiente de activacion.
	$consulta = "SELECT * FROM usuarios WHERE NOMBRE = '{$usuario}' AND ACTIVA = 'N';";
	$res = $conexion->query($consulta);
	$tuplas = $res->num_rows; // Contamos las tuplas, si hay al menos una significa que existe y estaba pendiente de activación y lo activamos, si no mostramos un mensaje.
	if($tuplas == 0)
	{	
		mysqli_close($conexion);//Cerramos conexion
		header("Location: error.php?e=".urlencode($texto["Activar_1"])); 
	}
	else
	{
		$consulta = "UPDATE	usuarios SET ACTIVA = 'S' WHERE NOMBRE = '".$usuario."';";
		$conexion->query($consulta); 
		mysqli_close($conexion);//Cerramos conexion
		header("Location: mensaje.php?m=".urlencode($texto["Activar_2"].$usuario.$texto["Activar_3"])); 
	}
?>