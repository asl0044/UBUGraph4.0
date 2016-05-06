<!DOCTYPE HTML>
<?php
	//Comprobamos que el usuario este logueado
	session_start();
	if (!isset($_SESSION["usuario"]))
	{ 
		header("Location: /");
	}
	require_once("funciones.php");
	//Cargamos el idioma
	require_once("../".idioma());
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" 	href="/estilos/estilo.css">
	</head>
    <body>	
        <div id="main" style="text-align: center;">
			<?php
				$conexion = conectarse();
				
				//Comprobamos si se nos ha pasado el id de un usuario como parametro (para los tutores)
				if(isset($_GET["id"]))
				{
					//Obtenemos el nombre para el id obtenido
					$id = $_GET["id"];
					$consulta = "SELECT NOMBRE FROM usuarios WHERE ID_USUARIO = {$id}";
					$res = $conexion->query($consulta);
					$reg = $res->fetch_assoc();
					$nombre = $reg["NOMBRE"];
				}
				//En caso contrario usamos los datos del usuario actual
				else
				{
					$id = $_SESSION["id_usuario"];
					$nombre = $_SESSION["usuario"];
				}
				
				echo "<h1>{$texto["Historico_1"]} {$nombre}</h1>";
				
				//Buscamos los grafos del usuario
				$consulta = "SELECT * FROM grafos WHERE ID_USUARIO = {$id} AND CALIFICAcION IS NOT NULL";
				$res = $conexion->query($consulta);
				$reg = $res->fetch_assoc();
				if($reg)
				{
					echo "\n<ul>";
					//Mostramos los grafos con su fecha, tipo y puntuacion y un enlace a su resolucion
					while($reg)
					{
						echo "\n<a  href=\"./{$reg["RESOLUCION"]}.php?id={$reg["ID_GRAFO"]}\"><li>{$reg["FECHA"]} --> {$reg["RESOLUCION"]} --> {$reg["CALIFICACION"]}/5</li></a>";
						$reg = $res->fetch_assoc();
					}
					echo "\n</ul>";
				}
				//Si no hay grafos mostramos un aviso
				else
				{
					echo "<p>{$texto["Historico_2"]}</p>";
				}
				
				
				mysqli_close($conexion);//Cerramos conexión
			?>
        </div>
	</body>
</html>