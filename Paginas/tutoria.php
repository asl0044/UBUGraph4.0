<!DOCTYPE HTML>
<?php
	//Comprobamos que el usuario esta logueado.
	session_start();
	if (!isset($_SESSION["usuario"]))
	{ 
		header("Location: /");
	}
	//Cargamos los idiomas.
	require_once("funciones.php");
	require_once("../".idioma());
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" 	href="/estilos/estilo.css">
	</head>
    <body>	
        <div id="main" style="text-align: center;">
			<?php
				//Datos de usuario de la sesion.
				$usuario = $_SESSION["usuario"];
				$id = $_SESSION["id_usuario"];
				
				//Conectamos con la BD
				$conexion = conectarse();
				
				//Obtenemos el tipo de cuenta del usuario.
				$consulta = "SELECT TIPO FROM usuarios WHERE NOMBRE = '{$usuario}';";
				$res = $conexion->query($consulta);
				$reg = $res->fetch_assoc();
				
				//Si es profesor mostramos sus grupos y sus alumnos.
				if($reg["TIPO"] == "P")
				{
					echo "<h1>{$texto["Tutoria_1"]}</h1>";
					
					//Formulario de creacion de grupos nuevos.
					echo "<form id=\"nuevo_grupo\" action=\"./crear_grupo.php\" method=\"post\">";
						echo "<label><b>{$texto["Tutoria_2"]}:</b></label>";
						echo "<input name=\"nombre\" size=\"50\" maxlenght=\"50\"/>";
						echo "<input type=\"submit\" value=\"{$texto["Tutoria_3"]}\">";
					echo "</form><br>";
					
					//Obtenemos los grupos del profesor
					$consulta = "SELECT ID_GRUPO, NOMBRE, CODIGO FROM grupos WHERE ID_TUTOR = '{$id}' ORDER BY NOMBRE;";
					$res = $conexion->query($consulta);
					$reg = $res->fetch_assoc();
					if($reg)
					{
						while($reg)
						{
							echo "<b>{$reg["NOMBRE"]}({$reg["CODIGO"]}): </b>";
							//Obtenemos los alumnos del grupo
							$consulta = "SELECT u.NOMBRE N, u.ID_USUARIO ID FROM pupilos p, usuarios u WHERE p.ID_USUARIO = u.ID_USUARIO AND p.ID_GRUPO = {$reg["ID_GRUPO"]} ORDER BY u.NOMBRE;";
							$res2 = $conexion->query($consulta);
							$reg2 = $res2->fetch_assoc();
							
							if($reg2)
							{
								while($reg2)
								{
									//Mostramos todos sus alumnos
									echo "\n<a href=\"./historico.php?id={$reg2["ID"]}\">{$reg2["N"]}</a> ";
									$reg2 = $res2->fetch_assoc();
								}
							}
							echo "<br><br>";
							$reg = $res->fetch_assoc();
						}
					}
					else
					{
						//Si no hay grupos mostramos un mensaje
						echo "<p>{$texto["Tutoria_4"]}</p>";
					}
				}
				//Si es un alumno mostramos los profesores que lo tutorizan
				else
				{					
					echo "<h1>{$texto["Tutoria_5"]}</h1>";
					
					//Formulario de agregacion de nuevos tutores.
					echo "<form id=\"nuevo_tutor\" action=\"./agregar_tutor.php\" method=\"post\">";
						echo "<label><b>{$texto["Tutoria_6"]}</b></label>";
						echo "<input name=\"codigo\" size=\"5\" maxlenght=\"11\"/>";
						echo "<input type=\"submit\" value=\"{$texto["Tutoria_7"]}\">";
					echo "</form>";
					
					$consulta = "SELECT NOMBRE FROM usuarios WHERE ID_USUARIO IN (SELECT ID_TUTOR FROM grupos WHERE ID_GRUPO IN (SELECT ID_GRUPO FROM pupilos WHERE ID_USUARIO = (SELECT ID_USUARIO FROM usuarios WHERE NOMBRE = '{$_SESSION["usuario"]}')));";
					$res = $conexion->query($consulta);
					$reg = $res->fetch_assoc();
					
					while($reg)
					{
						echo "<ul>";
							echo "<li>{$reg["NOMBRE"]}</li>";							
						echo "</ul>";
						$reg = $res->fetch_assoc();
					}	
				}
				
				mysqli_close($conexion);//Cerramos conexión
			?>
        </div>
	</body>
</html>