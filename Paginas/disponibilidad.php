<!DOCTYPE HTML>
<html>
	<head>
		<?php
			require_once("funciones.php");
			//Cargamos el idioma correcto
			require_once("../".idioma());
		?>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">	
		<title><?php echo $texto["Diponibilidad_1"]; ?></title>
	</head>
	<body>
	<?php
		$conexion = conectarse();
		//Comprobamos si el id proporcionado existe ya y mostramos un mensaje en consecuencia.
		if(isset($_GET["id"]) && ($_GET["id"] != ""))
		{
			$consulta = "SELECT * FROM usuarios WHERE UPPER(NOMBRE) = UPPER('".$_GET["id"]."');";
			$res = $conexion->query($consulta);
			$tuplas = $res->num_rows;
			if($tuplas == 0)
			{
				echo "<span style=\"color:green;font-weight: bold;\">".$texto["Diponibilidad_2"].$_GET["id"]." ".$texto["Diponibilidad_3"]."</span>";
			}
			else
			{
				echo "<span style=\"color:red;font-weight: bold;\">".$texto["Diponibilidad_4"]."</span>";
			}
		}
		else
		{
			echo "<span style=\"color:red;font-weight: bold;\">".$texto["Diponibilidad_5"]."</span>";
		}
		echo "<BR>";
		//Y lo mismo para el correo
		if(isset($_GET["correo"]) && ($_GET["correo"] != ""))
		{
			$consulta = "SELECT * FROM usuarios WHERE UPPER(CORREO) = UPPER('".$_GET["correo"]."');";
			$res = $conexion->query($consulta);
			$tuplas = $res->num_rows;
			if($tuplas == 0)
			{
				echo "<span style=\"color:green;font-weight: bold;\">".$texto["Diponibilidad_6"].$_GET["correo"]." ".$texto["Diponibilidad_7"]."</span>";
			}
			else
			{
				echo "<span style=\"color:red;font-weight: bold;\">".$texto["Diponibilidad_8"]."</span>";
			}
		}
		else
		{
			echo "<span style=\"color:red;font-weight: bold;\">".$texto["Diponibilidad_9"]."</span>";
		}
		mysqli_close($conexion);  //Cierra la conexion con la base de datos
	?>
	</body>
</html>