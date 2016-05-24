<!DOCTYPE HTML>
<?php
	session_start();
	if (!isset($_SESSION["usuario"]))
	{ 
		header("Location: /");
	}
	require_once("funciones.php");
	
	require_once("funcionesGeneraPregunta.php");

?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" 	href="/estilos/estilo.css">		
	</head>
	<body>
		<div id="main">
		<?php
			
			$numAct = $_POST["numActividades"];
			$probabilidad = $_POST["probabilidad"];
			$ids = $ids = array(0 => "A", 1 => "B", 2 => "C", 3 => "D", 4 => "E", 5 => "F", 6 => "G", 7 => "H", 8 => "I", 9 => "J", 10 => "K", 11 => "L", 12 => "M", 13 => "N", 14 => "O", 15 => "P", 16 => "Q", 17 => "R", 18 => "S", 19 => "T", 20 => "U", 21 => "V", 22 => "W", 23 => "X", 24 => "Y", 25 => "Z");
		
			$contNumericas = $_POST["numericas"];
			$contVF = $_POST["vf"];
			$contSelSimple = $_POST["selSimple"];
			$contSelMult = $_POST["selMult"];
			
			$numPreguntas = $contNumericas + $contVF + $contSelSimple + $contSelMult;
			
			$file = fopen("./XML/prueba.xml","w");
			fputs($file,"<?xml version=\"1.0\" ?>");
			fputs($file,"\n<quiz>");
					
			for($i=1;$i<=$numPreguntas;$i++){
				$nombres = array();
				$precedencias = array();
				$duraciones = array();
				
				
				generarTablaPrecedencias($numAct,$probabilidad, $ids, $nombres, $precedencias, $duraciones);
				$tabla = dibujarTabla($nombres, $precedencias, $duraciones);
				$grafo = null;
				
				$hecha = false;
				if($contNumericas > 0){
					$data = obtenerGrafoRoy($nombres,$duraciones,$precedencias,$grafo);
					$preg_resp = generarPreguntaNumerica($nombres,$grafo);
					$tipo = "numerical";
					$hecha = true;
					$contNumericas--;
				}
				else if ($contVF > 0 && !$hecha){
					$data = obtenerGrafoPert($nombres,$duraciones,$precedencias,$grafo);
					$preg_resp = generarPreguntaVF($grafo);
					$tipo = "truefalse";
					$hecha = true;
					$contVF--;
				}
				else if($contSelSimple > 0 && !$hecha){
					$data = obtenerGrafoRoy($nombres,$duraciones,$precedencias,$grafo);
					$preg_resp = generarPreguntaSelSimple($grafo);
					$tipo = "multichoice";
					$hecha = true;
					$contSelSimple--;
				}
				else if($contSelMult > 0 && !$hecha){
					$data = obtenerGrafoRoy($nombres,$duraciones,$precedencias,$grafo);
					$preg_resp = generarPreguntaSelMult($nombres,$grafo);
					$tipo = "multichoice";
					$hecha = true;
					$contSelMult--;
				}
				
				$file = escribirPregunta($file,$tabla,$preg_resp,$tipo,$i,$data);
				
			}
			fputs($file,"\n</quiz>");
			echo "El fichero XML se ha creado correctamente.";
			
		?>
	</body>
</html>