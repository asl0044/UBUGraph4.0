<!DOCTYPE HTML>
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
?>
<html>	
	<head>
		<link rel="stylesheet" type="text/css" 	href="/estilos/estilo.css">
	</head>
	<body>
		 <div id="main" style="text-align: center;">
			<?php
				$nombres = array();
				$precedencias = array();
				$duraciones = array();
				
				$conexion = conectarse();
				
				//Buscamos primero si hay grafos pendientes de resolver para este usuario.
				$consulta = "SELECT * FROM grafos WHERE CALIFICACION IS NULL AND ID_USUARIO = {$_SESSION["id_usuario"]};";
				$result = $conexion->query($consulta);
				$tuplas = $result->num_rows;
				//En caso de haber alguno pendiente cargamos los datos de la BD
				if($tuplas != 0)
				{
					$reg = $result->fetch_assoc();
					$metodo = $reg["RESOLUCION"];
					$consulta = "SELECT * FROM nodos WHERE ID_GRAFO = {$reg["ID_GRAFO"]};";
					$result = $conexion->query($consulta);
					$reg = $result->fetch_assoc();
					$idGrafo = $reg["ID_GRAFO"];
					
					while($reg)
					{
						array_push($nombres, $reg["NOMBRE"]);
						array_push($precedencias, $reg["PRECEDENCIAS"]);
						array_push($duraciones, $reg["DURACION"]);
						$reg = $result->fetch_assoc();
					}
				}
				//En caso contrario cargamos los parámetros del formulario para generar un grafo nuevo
				else if(isset($_POST["numActividades"]) && isset($_POST["probabilidad"]) && isset($_POST["metodo"]))
				{
					$numAct = $_POST["numActividades"];
					$metodo = $_POST["metodo"];
					$probabilidad = $_POST["probabilidad"];
					$ids = array(0 => "A", 1 => "B", 2 => "C", 3 => "D", 4 => "E", 5 => "F", 6 => "G", 7 => "H", 8 => "I", 9 => "J", 10 => "K", 11 => "L", 12 => "M", 13 => "N", 14 => "O", 15 => "P", 16 => "Q", 17 => "R", 18 => "S", 19 => "T", 20 => "U", 21 => "V", 22 => "W", 23 => "X", 24 => "Y", 25 => "Z");
					
					//Insertamos el nuevo grafo en la BD
					$consulta = "INSERT INTO grafos(ID_GRAFO, ID_USUARIO, RESOLUCION, CALIFICACION, FECHA) VALUES(0, {$_SESSION["id_usuario"]}, UPPER('{$metodo}'), NULL, NOW());";
					$result = $conexion->query($consulta);
					
					$idGrafo = $conexion->insert_id;
					
					//Generamos la tabla de precedencias					
					for($i = 0; $i < $numAct; $i++)
					{
						array_push($nombres, $ids[$i]);
						
						$p = "";
						for($j = 0; $j < $i; $j++)
						{
							if($j != $i)
							{
								if(rand(1,100) <= $probabilidad)
								{
									if($p == "")
									{
										$p = $nombres[$j];
									}
									else
									{
										$p = $p." ".$nombres[$j];
									}
								}
							}
						}
						
						array_push($precedencias, $p);
						
						$duracionNodo = rand(1,25);
						array_push($duraciones, $duracionNodo);
						
						//Guardamos cada uno de los nodos generados en la BD
						$consulta = "INSERT INTO nodos(NOMBRE, ID_GRAFO, DURACION, PRECEDENCIAS) VALUES('{$ids[$i]}', {$idGrafo}, {$duracionNodo}, '{$p}');";
						$conexion->query($consulta);
					}
				}
				//Sin tampoco hay datos del formulario, entonces no tenemos datos y avisamos del error.
				else
				{
					header("Location: ../paginas/error.php?e=".urlencode($texto["Generando_1"]));
				}
				
				//Mostramos al usuario la tabla de precedencias.
				echo "\n<h2 style=\"text-align: center;\">{$texto["Generando_2"]}  (".strtoupper($metodo).")</h2>";
				echo "\n<table style=\"width: 100%;\">";
					echo "\n<tr>";
						echo "\n<th>{$texto["Generando_3"]}</th>";
						echo "\n<th>{$texto["Generando_4"]}</th>";
						echo "\n<th>{$texto["Generando_5"]}</th>";
					echo "\n</tr>";
					for($i = 0; $i < sizeof($nombres); $i++)
					{
						echo "\n<tr>";
							echo "\n<td>{$nombres[$i]}</td>";
							echo "\n<td>{$precedencias[$i]}</td>";
							echo "\n<td>{$duraciones[$i]}</td>";
						echo "\n</tr>";
					}
				echo "\n</table><br>";
				
				if($metodo == "pert")
					$metodo = "pertCorregido";
				
				//Formulario de resolucion del grafo.
				echo "<form id=\"generar_problema\" action=\"./{$metodo}.php\" method=\"post\">";			

				//Campos ocultos necesarios para resolver posteriormente el grafo
				if($nombres != null)
				{
					for($i = 0; $i < sizeof($nombres); $i++)
					{
						echo "\n<input hidden name=\"nombres[]\" value=\"{$nombres[$i]}\"/>";
						echo "\n<input hidden name=\"precedencias[]\" value=\"{$precedencias[$i]}\"/>";
						echo "\n<input hidden name=\"duraciones[]\" value=\"{$duraciones[$i]}\"/>";
					}
				}
				
				//Comprobamos si ya hay preguntas generadas para este grafo
				$consulta = "SELECT * FROM preguntas WHERE ID_GRAFO = {$idGrafo};";		
				$result = $conexion->query($consulta);
				$tuplas = $result->num_rows;
				//Si no las hay las generamos ahora
				if($tuplas == 0)
				{
					$x = $nombres[rand(0,count($nombres) -1)];
					$y = $nombres[rand(0,count($nombres) -1)];
					$z = $nombres[rand(0,count($nombres) -1)];
					
					//Y las guardamos en la BD
					$consulta = "INSERT INTO preguntas(ID_GRAFO, NOMBRE_1, NOMBRE_2, NOMBRE_3) VALUES({$idGrafo}, '{$x}', '{$y}', '{$z}');";
					$conexion->query($consulta);
				}
				//Si si que las hay, simplemente las leemos
				else
				{
					$res = $result->fetch_assoc();
					
					$x = $res["NOMBRE_1"];
					$y = $res["NOMBRE_2"];
					$z = $res["NOMBRE_3"];
				}
		
				//Cerramos la conexion con la BD
				mysqli_close($conexion);
	
				//Mostramos al usuario las preguntas que debe responder y los campos para hacerlo.
				echo "\n<h2 style=\"text-align: center;\">{$texto["Generando_12"]}</h2>";
				
				echo "\n<label>{$texto["Generando_6"]} {$x}?</label>";
				echo "<input type=\"number\" required step=\"1\" name=\"pregunta1\" min=\"0\" value=\"-1\"><br>";
				
				echo "\n<label>{$texto["Generando_7"]} {$y}?</label>";
				echo "<input type=\"number\" required step=\"1\" name=\"pregunta2\" min=\"0\" value=\"-1\"><br>";
				
				echo "\n<label>{$texto["Generando_8"]} {$z}?</label>";
				echo "<input type=\"number\" required step=\"1\" name=\"pregunta3\" min=\"0\" value=\"-1\"><br>";
				
				echo "\n<label>{$texto["Generando_9"]}</label>";
				echo "<input type=\"number\" required step=\"1\" name=\"pregunta4\" min=\"0\" value=\"-1\"><br>";
				
				echo "\n<label>{$texto["Generando_10"]}</label>";
				echo "<input type=\"text\" required name=\"pregunta5\" maxlength=\"50\">";
				
				//Este campo indica que se debe resolver el formulario y es necesario para su correcto procesamiento
				echo "\n<input hidden name=\"resolver\" value=\"s\"/>";
				//Por ultimo mostramos el boton de envio del formulario.
				echo "\n<br><br><input type=\"submit\" value=\"{$texto["Generando_11"]}\"/>";
				echo "</form>";
			?>
		</div>
	</body>
</html>