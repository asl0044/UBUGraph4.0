<!DOCTYPE html>  
<?php 
	//Comprobamos que el usuario este logueado y que es administrador
	session_start();
	if (!isset($_SESSION["usuario"]) || !isset($_SESSION["administrador"]) || ($_SESSION["administrador"] != "si"))
	{
		header("Location: /");
	}
	//Cargamos los idiomas.
	require_once("funciones.php");
	require_once("../".idioma());
?>
<html>
	<head>
    	<link href="/Estilos/estilo.css" rel="stylesheet" type="text/css" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<title><?php echo $texto["Administracion_1"];?></title>		
	</head>
    <body onLoad="MM_preloadImages('/Imagenes/boton2.jpg')">
		<!-- div con el logout y los diomas-->
		<div class="cabecera">
    		<?php 
    			cabecera();
    		?>
    	</div>
		
		<!--Mostramos una tabla con la lista de los usuarios-->
    	<div class="contenido" style="text-align: center;">
			<h2><?php echo $texto["Administracion_2"];?></h2>
			<table>
				<tr>
					<!--Las cabeceras permiten reodrenar la lista de los usuarios por cada uno de los campos-->
					<th><a href="./administracion.php?o=NOMBRE"><?php echo $texto["Administracion_3"];?></a></th>
					<th><a href="./administracion.php?o=CORREO"><?php echo $texto["Administracion_4"];?></a></th>
					<th><a href="./administracion.php?o=TIPO"><?php echo $texto["Administracion_5"];?></a></th>
					<th><a href="./administracion.php?o=ACTIVA"><?php echo $texto["Administracion_6"];?></a></th>
				</tr>
				<?php
					//Obtenemos el campo para ordenar si lo hubiera, en caso de no haberlo ordenamos por nomnbre
					$orden = "NOMBRE";
					if(isset($_GET["o"]))
					{
						$orden = $_GET["o"];
					}
					//Obtenemos la lista de los usuarios con el orden adecuado
					$sql = "SELECT * FROM usuarios ORDER BY {$orden}";
					$conexion = conectarse();
					$res = $conexion->query($sql);
					$reg = $res->fetch_assoc();
					
					//Y los mostramos
					while($reg)
					{
						echo "<tr>";
							echo "<td><a href=\"./editar.php?n={$reg["NOMBRE"]}\">{$reg["NOMBRE"]}</a></td>";
							echo "<td>{$reg["CORREO"]}</td>";
							echo "<td>{$reg["TIPO"]}</td>";
							echo "<td>{$reg["ACTIVA"]}</td>";
						echo "</tr>";
						$reg = $res->fetch_assoc();
					}
				?>
			</table>
    	</div>
	</body>
</html>