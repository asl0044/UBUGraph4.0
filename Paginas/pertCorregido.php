<!DOCTYPE HTML>
<html>	
	<head>
		<link rel="stylesheet" type="text/css" 	href="/estilos/estilo.css">
		<script type="text/javascript">
			function mostrar()
			{
		    	document.getElementById('oculto').style.display='block';
				document.getElementById('oculto').style.position='absolute';
				document.getElementById('oculto').style.top='0';
				document.getElementById('oculto').style.left='0';
				document.getElementById('oculto').style.zIndex='25';
				document.getElementById('oculto').style.width='100vw';
				document.getElementById('oculto').style.height='100vh';
			}
			
			function ocultar()
			{
		    	document.getElementById('oculto').style.display='none';
			}
		</script>
	</head>
	<body>
		<?php
			//Comprobamos que el usuario esta logueado
			session_start();
			if (!isset($_SESSION["usuario"]))
			{ 
				header("Location: /");
			}
			require_once ("Image/GraphViz.php");
			require_once("./Actividad.php");
			require_once("./funciones.php");
			require_once("./funcionesPert.php");
			//Cargamos el idioma
			require_once("../".idioma());
			
			//Comprobamos si se nos ha pasdo el id de un grafo ya resuelto.
			if(isset($_GET["id"]))
			{
				$idGrafo = $_GET["id"];		
				$conexion = conectarse();
				
				//Buscamos la informacion del grafo
				$consulta = "SELECT GRAFO from grafos WHERE ID_GRAFO = {$idGrafo} AND GRAFO IS NOT NULL";
				$result = $conexion->query($consulta);
				$tuplas = $result->num_rows;
				//Comprobamos que existen los datos
				if($tuplas != 0)
				{
					//Mostramos el grafo
					$reg = $result->fetch_assoc();
					echo "<div class=\"ampliable\" onClick=\"mostrar();\">{$reg["GRAFO"]}</div>";
					$grafo = preg_replace("/<svg width=\"\d+pt\" height=\"\d+pt\"/","<svg style=\"max-height: none;\" height=\"100%\"",$reg["GRAFO"]);
					echo "<div onClick=\"ocultar();\" id=\"oculto\" class=\"oculto\">".$grafo."</div>";
					mysqli_close($conexion);
					
					//Mostramos la evaluacion para este grafo
					evaluar($idGrafo);
				}
				//Si no existen los datos mostramos un error
				else
				{
					mysqli_close($conexion);
					header("Location: ../paginas/error.php?e=".urlencode($texto["Pert_1"]));
				}
			}
			//Si no tenemos id comprobamos que tenemos la tabla de precedencias
			else if(isset($_POST["nombres"]) && isset($_POST["precedencias"]) && isset($_POST["duraciones"]))
			{
				$nombres = $_POST["nombres"];
				$precedencias = $_POST["precedencias"];
				$precedenciasRoy = $_POST["precedencias"];
				$duraciones = $_POST["duraciones"];
				$resolver = false;
				
				//Si necesitamos obtener las respuestas correctas a las preguntas (resolver) comprobamos que tambien tenemos las respuestas que ha dado el usuario.
				if ((isset($_POST["resolver"])) && (isset($_POST["pregunta1"])) && (isset($_POST["pregunta2"])) && (isset($_POST["pregunta3"])) && (isset($_POST["pregunta4"])) && (isset($_POST["pregunta5"])))
				{	
					$resolver = true;
					$conexion = conectarse();
					
					//Buscamos las respuestas del usuario.
					$consulta = "SELECT * FROM respuestas WHERE ID_GRAFO = (SELECT ID_GRAFO FROM grafos WHERE CALIFICACION IS NULL AND ID_USUARIO = {$_SESSION["id_usuario"]})";
					$result = $conexion->query($consulta);
					$tuplas = $result->num_rows;			
					//Si las respuestas no están almacenadas lo hacemos ahora.
					if($tuplas == 0)
					{
						$consulta = "INSERT INTO respuestas(ID_GRAFO, RESPUESTA_1, RESPUESTA_2, RESPUESTA_3, RESPUESTA_4, RESPUESTA_5) VALUES((SELECT ID_GRAFO FROM grafos WHERE CALIFICACION IS NULL AND ID_USUARIO = {$_SESSION["id_usuario"]}), {$_POST["pregunta1"]}, {$_POST["pregunta2"]}, {$_POST["pregunta3"]}, {$_POST["pregunta4"]}, UPPER(REPLACE('{$_POST["pregunta5"]}', ' ', '')))";
						$conexion->query($consulta);
					}
					
					//Buscamos las preguntas del grafo.
					$consulta = "SELECT * FROM preguntas WHERE ID_GRAFO = (SELECT ID_GRAFO FROM grafos WHERE CALIFICACION IS NULL AND ID_USUARIO = {$_SESSION["id_usuario"]})";
					$result = $conexion->query($consulta);
					$tuplas = $result->num_rows;
					if($tuplas != 0)
					{
						$preguntas = $result->fetch_assoc();
					}
					//Si no hay preguntas mostramos un error.
					else
					{
						mysqli_close($conexion);
						header("Location: ../paginas/error.php?e=".urlencode($texto["Pert_2"]));
					}
				}		
					
				//////////////RESOLUCION PERT//////////////
				$precedenciasRoy = $precedencias;
				/////Generamos el conjunto de Actividades////
				$grafo = generarActividades($nombres,$precedencias,$duraciones);
				
				//Establecemos las actividades posteriores
				
				establecerPrecedenciasPert($grafo,$nombres, $duraciones, $precedencias);
		
				$nodos = establecerFicticias($grafo,$nombres, $duraciones, $precedencias,$precedenciasRoy);
				
				if($resolver == false){
					$conexion = null;
					$preguntas = null;
				}
				
				$gv = generarGrafoPert($grafo,$nodos,$resolver,$conexion,$preguntas);
								
				$data = dibujarGrafo($gv);
								
				//Si es necesario guardamos el grafo en la BD
				if($resolver)
				{
					$consulta = "UPDATE grafos SET GRAFO = '{$data}' WHERE ID_GRAFO = {$preguntas["ID_GRAFO"]};";
					$conexion->query($consulta);
					mysqli_close($conexion);
				}
				
				//Mostramos el grafo
				//$reg = $result->fetch_assoc();
				echo "<div class=\"ampliable\" onClick=\"mostrar();\">{$data}</div>";
				$grafo = preg_replace("/<svg width=\"\d+pt\" height=\"\d+pt\"/","<svg style=\"max-height: none;\" height=\"100%\"",$data);
				echo "<div onClick=\"ocultar();\" id=\"oculto\" class=\"oculto\">".$grafo."</div>";
				
				//Mostramos las preguntas y respuestas correspondientes si es necesario
				if($resolver)
				{
					$idGrafo = $preguntas["ID_GRAFO"];
					evaluar($idGrafo);
				}
			}
			//Si no tenemos la tabla de precedencias mostramos un error.
			else
			{
				header("Location: ../paginas/error.php?e=".urlencode($texto["Pert_3"]));
			}
				
		?>
	</body>
</html>