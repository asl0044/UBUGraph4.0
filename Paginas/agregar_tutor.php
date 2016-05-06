<?php
	//Comprobamos que el usuario esta logueado
	session_start();
	if (!isset($_SESSION["usuario"]))
	{
		header("Location: /");
	}
	
	require_once("funciones.php");
	//Cargamos el idioma
	require_once("../".idioma());
	echo header('Content-Type: text/html; charset=utf-8'); 
		
	//Comprobamos que hemos recibido un codigo de tutor
	if(isset($_POST['codigo']))
	{
		$codigo = $_POST['codigo'];
	}
	//Si no hay codigo mostramos un error
	else 
	{
		header("Location: error.php?e=".urlencode($texto["Agregar_tutor_1"]));
	}
		
	$conexion = conectarse();
	//Comprobamos que existe el grupo para ese codigo
	$consulta = "SELECT ID_GRUPO FROM grupos WHERE CODIGO = '{$codigo}';";
	$res = $conexion->query($consulta);
	$tuplas = $res->num_rows;
	
	//Si existe el codigo, agregamos el alumno
	if($tuplas == 1)
	{
		$reg = $res->fetch_assoc();
		$IDgrupo = $reg["ID_GRUPO"];
		
		$consulta = "INSERT INTO pupilos(ID_USUARIO, ID_GRUPO) VALUES({$_SESSION["id_usuario"]}, {$IDgrupo});";
		$result = $conexion->query($consulta);
	}
	//En caso contrario mostramos un error.
	else
	{
		mysqli_close($conexion);
		header("Location: error.php?e=".urlencode($texto["Agregar_tutor_1"]));
	}
	
	mysqli_close($conexion);
	
	//Volvemos donde estabamos
	header("Location: ./tutoria.php");
?>