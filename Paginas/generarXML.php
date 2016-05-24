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
	require("../".idioma());
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" 	href="/estilos/estilo.css">		
	</head>
    <body>	
        <div id="main">
			<!-- Formulario de seleccion de propiedades del grafo a genrerar -->
			<form id="formGenerarXML" name="generarXML" action="/paginas/generaPregunta.php" method="post">
	            <label class="ajustado"><b><?php echo $texto["Generar_1"]; ?></b></label>
	            <input class="ajustado" required="required" type="number" name="numActividades" value="5" min="2" max="15"><BR>
	             
	            <label class="ajustado"><b><?php echo $texto["Generar_2"]; ?></b></label>
				<input class="ajustado" required="required" type="number" name="probabilidad" value="25" min="0" max="75"><BR> 
				
				<label class="ajustado"><b>Número de preguntas numéricas</b></label>
				<input class="ajustado" required="required" type="number" name="numericas" min="0"><BR>
				
				<label class="ajustado"><b>Número de preguntas de verdadero o falso</b></label>
				<input class="ajustado" required="required" type="number" name="vf" min="0"><BR>
				
				<label class="ajustado"><b>Número de preguntas de selección múltiple de una sola respuesta</b></label>
				<input class="ajustado" required="required" type="number" name="selSimple" min="0"><BR>
				
				<label class="ajustado"><b>Número de preguntas de selección múltiple de varias respuestas</b></label>
				<input class="ajustado" required="required" type="number" name="selMult" min="0"><BR>
				
				<input class="ajustado" type="submit" value="Generar XML">
	        </form>
			
        </div>
	</body>
</html>