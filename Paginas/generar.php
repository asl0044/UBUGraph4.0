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
	
	$conexion = conectarse();
	
	//Buscamos grafos pendientes de resolver por el usuario
	$consulta = "SELECT * FROM grafos WHERE CALIFICACION IS NULL AND ID_USUARIO = {$_SESSION["id_usuario"]};";
	$result = $conexion->query($consulta);
	$tuplas = $result->num_rows;
	
	mysqli_close($conexion); //Cerramos conexion
	//Si hay grafos pendientes redirigimos a generando
	if($tuplas != 0)
	{
		header("Location: /paginas/generando.php");
	}
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" 	href="/estilos/estilo.css">		
	</head>
    <body>	
        <div id="main">
			<!-- Formulario de seleccion de propiedades del grafo a genrerar -->
			<form id="formGenerarProblema" name="generarProblema" action="/paginas/generando.php" method="post">
	            <label class="ajustado"><b><?php echo $texto["Generar_1"]; ?></b></label>
	            <input class="ajustado" required="required" type="number" name="numActividades" value="5" min="2" max="15"><BR>
	             
	            <label class="ajustado"><b><?php echo $texto["Generar_2"]; ?></b></label>
				<input class="ajustado" required="required" type="number" name="probabilidad" value="25" min="0" max="75"><BR>
				
				<label class="ajustado"><b><?php echo $texto["Generar_3"]; ?></b></label>
				<select name="metodo" class="ajustado">
					<option value="pert">PERT</option>
					<option value="roy">ROY</option>
				</select>
				
	 			<input class="ajustado" type="submit" value="<?php echo $texto["Generar_4"]; ?>">
	        </form>
			
        </div>
	</body>
</html>