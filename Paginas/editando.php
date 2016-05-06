<?php
	//Comprobamos que el usuario esta logueado y que es administrador
	session_start();
	if (!isset($_SESSION["usuario"]) || !isset($_SESSION["administrador"]) || ($_SESSION["administrador"] != "si"))
	{
		header("Location: /");
	}
	
	require_once("funciones.php");
	//Cargamos el idioma
	require_once("../".idioma());	
	
	//Comprobamos que hemos recibido un usuario
	if(isset($_POST["usuario"]))
	{
		$usuario = $_POST['usuario'];
		
		//Obtenemos los datos del usuario
		$sql = "SELECT TIPO FROM usuarios WHERE NOMBRE = '{$usuario}'";
		$conexion = conectarse();
		$res = $conexion->query($sql);
		$reg = $res->fetch_assoc();		
		
		//Si se ha marcado para borrar lo borramos (salvo si es administrador)
		if(isset($_POST["borrar"]) && ($_POST["borrar"] == "S"))
		{
			$conexion->query("DELETE FROM usuarios WHERE TIPO <> 'G' AND NOMBRE = '{$usuario}'");
			mysqli_close($conexion);
			header("Location: ./editar.php?n={$usuario}&m=".urlencode($texto["Editando_1"]));
		}
		//Si no se ha marcado para borra, pero se ha recibido alguna modificacion se procesan
		else if(($_POST["con1"] != "") || ($_POST["correo"] != "") || isset($_POST["tipo"]) || isset($_POST["activa"]))
		{
			$notificacion = "";//Variable que contiene un mensaje con los cambios realizados (o no)
			//Si hemos recibido contraseña
			if($_POST["con1"] != "")
			{
				//Y coincide con la repeticion la cambiamos
				if($_POST['con1'] == $_POST['con2'])
				{
					$conexion->query("UPDATE usuarios SET CLAVE = AES_ENCRYPT('{$_POST['con1']}',UPPER('{$usuario}')) WHERE NOMBRE = '{$usuario}'");
					$notificacion = $notificacion.$texto["Editando_2"]." ";
				}
				//Si no coincide lo anotamos
				else
				{
					$notificacion = $notificacion.$texto["Editando_3"]." ";
				}
			}
			
			//Si se ha recibido un correo
			if($_POST["correo"] != "")
			{
				//Comprobamos que nadie mas lo tenga
				$sql = "SELECT * FROM usuarios WHERE CORREO = '{$_POST["correo"]}'";
				$res = $conexion->query($sql);
				$tuplas = $res->num_rows;
				
				//Si nadie lo tiene lo cambiamos
				if($tuplas == 0)
				{
					$conexion->query("UPDATE usuarios SET CORREO = '{$_POST["correo"]}' WHERE NOMBRE = '{$usuario}'");
					$notificacion = $notificacion.$texto["Editando_4"]." ";
				}
				else
				{
					$notificacion = $notificacion.$texto["Editando_5"]." ";
				}
			}
			
			//Si se ha recibido un tipo de cuenta
			if(isset($_POST["tipo"]))
			{
				//Y el usuario no es administrador lo cambiamos
				if($reg["TIPO"] != "G")
				{
					$conexion->query("UPDATE usuarios SET TIPO = '{$_POST['tipo']}' WHERE NOMBRE = '{$usuario}'");
					$notificacion = $notificacion.$texto["Editando_6"]." ";
				}
				else
				{
					$notificacion = $notificacion.$texto["Editando_7"]." ";
				}
			}
			
			//Si se ha recibido una activacion/desactivacion
			if(isset($_POST["activa"]))
			{
				//Y el usuario no es un administrador lo cambiamos
				if($reg["TIPO"] != "G")
				{
					$conexion->query("UPDATE usuarios SET ACTIVA = '{$_POST["activa"]}' WHERE NOMBRE = '{$usuario}'");
					$notificacion = $notificacion.$texto["Editando_8"]." ";
				}
				else
				{
					$notificacion = $notificacion.$texto["Editando_9"]." ";
				}
			}
			
			mysqli_close($conexion);
			//Volvemos al formulario de edicion y mostramos un mensaje con los cambios realizados o no
			header("Location: ./editar.php?n={$usuario}&m=".urlencode($notificacion));
		}
		//Si no se recibe ningun cambio volvemos con un mensaje
		else
		{
			mysqli_close($conexion);
			header("Location: ./editar.php?n={$usuario}&m=".urlencode($texto["Editando_10"]));
		}
	}
	//En caso contrario volvemos
	else 
	{
		header("Location: ./editar.php?n={$usuario}&m=".urlencode($texto["Editando_11"]));
	}
?>