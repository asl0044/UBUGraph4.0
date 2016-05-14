<!DOCTYPE HTML>
<?php
	session_start();
	if (!isset($_SESSION["usuario"]))
	{ 
		header("Location: /");
	}
	require_once("funciones.php");
	require_once("funcionesRoy.php");

	//Cargamos el idioma
	require_once("../".idioma());
	require 'mustache/src/Mustache/Autoloader.php';
	Mustache_Autoloader::register();

?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" 	href="/estilos/estilo.css">		
	</head>
	<body>
		<div id="main">
		<?php
			
			$numAct = $_POST["numActividades"];
			$metodo = $_POST["metodo"];
			$probabilidad = $_POST["probabilidad"];
			$ids = $ids = array(0 => "A", 1 => "B", 2 => "C", 3 => "D", 4 => "E", 5 => "F", 6 => "G", 7 => "H", 8 => "I", 9 => "J", 10 => "K", 11 => "L", 12 => "M", 13 => "N", 14 => "O", 15 => "P", 16 => "Q", 17 => "R", 18 => "S", 19 => "T", 20 => "U", 21 => "V", 22 => "W", 23 => "X", 24 => "Y", 25 => "Z");
			
			$numPreguntas = $_POST["numPreguntas"];
			
			$file = fopen("./XML/prueba.xml","w");
			fputs($file,"<?xml version=\"1.0\" ?>");
			fputs($file,"\n<quiz>");
			for($i=1;$i<=$numPreguntas;$i++){
				$nombres = array();
				$precedencias = array();
				$duraciones = array();
				
				generarTablaPrecedencias($numAct,$probabilidad, $ids, $nombres, $precedencias, $duraciones);
				
				
				echo "Estamos en la iteracion $i \n";
				$actividadAleatoria = $nombres[rand(0,sizeof($nombres)-1)];
				$actividad = array("actividad"=>$actividadAleatoria);
				$pregunta1  ="¿Cuál es la holgura de la actividad {{actividad}}?";
				$tablaXML = "<tr>
								<td>{{nombre}}</td>
								<td>{{precedencia}}</td>
								<td>{{duracion}}</td>
							</tr>
							";
				$tabla = "";
				//Para usar el Mustache hay que bajarlo y poner en la carpeta correspondiente y poner el require arriba y el composer.json
				$m = new Mustache_Engine;
				$pregunta = $m->render($pregunta1,$actividad);
				
				
				for($j=0; $j < sizeof($nombres);$j++){
					$filas = array("nombre"=>$nombres[$j],"precedencia"=>$precedencias[$j], "duracion"=>$duraciones[$j]);
					$tabla .= $m->render($tablaXML, $filas);
				}
				
				$grafo = generarNodos($nombres,$duraciones,$precedencias);
				establecerPrecedencias($grafo,$nombres,$precedencias);
				calcularTiempos($grafo);
				$gv = generarGrafo($grafo);
				$data = dibujarGrafo($gv);
				$respuesta = $grafo[$actividadAleatoria]->getHolguraTotal();			
				
				
				fputs($file,"\n\t<question type=\"numerical\">");	
				fputs($file,"\n\t\t<name>\n\t\t\t<text>Pregunta $i</text>\n\t\t</name>");
				fputs($file,"\n\t\t<questiontext format=\"html\">
							<text><![CDATA[<h2 style=\"text-align: center;\">{$texto["Generando_2"]}  
							(".strtoupper($metodo).")</h2>
							<table align=\"center\" border=\"1\" style=\"width: 100%\">
							<tr>
								<th>{$texto["Generando_3"]}</th>
								<th>{$texto["Generando_4"]}</th>
								<th>{$texto["Generando_5"]}</th>
							</tr>
							$tabla
							</table><br>
							]]>
							$pregunta
					</text>");
				fputs($file,"\n\t\t</questiontext>");
				fputs($file,"\n\t\t<generalfeedback format=\"html\">");
				fputs($file,"\n\t\t\t<text><![CDATA[{$data}<br>]]></text>");
				fputs($file,"\n\t\t</generalfeedback>");
				fputs($file,"\n\t\t<answer fraction=\"100\">\n\t\t\t<text>{$respuesta}</text>\n\t\t</answer>" );
				fputs($file,"\n\t</question>");
			}
			fputs($file,"\n</quiz>");
			
		?>
	</body>
</html>