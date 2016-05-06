<!DOCTYPE html> 
<html>
	<head>	
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	   	<link href="../Estilos/estilo.css" rel="stylesheet" type="text/css">
		<?php
			require_once("funciones.php");
			//Cargamos los idiomas.
			require_once("../".idioma());
		?>
	    <title><?php echo $texto["Recuperar_1"]; ?></title>
	</head>
	<body>
		<!--Div con la seleccion de idiomas-->
		<div class="centrar" class="idiomas">
			<?php pintarIdiomas(); ?>
		</div>
		
		<!-- Div con el formulario de recuperacion -->
		<div class="centro">
			<h1><?php echo $texto["Recuperar_2"]; ?></h1>
			<form id="registro" action="recuperando.php" method="post">
				<label class="ajustado"><b><?php echo $texto["Recuperar_3"]; ?>:</b></label>
				<input class="ajustado" type="email" required="required" name="correo" value="" maxlength="50" size="50"/>
				
				<input class="ajustado" type="submit" value="<?php echo $texto["Recuperar_4"]; ?>" onClick="return validar();"/>
			</form>
		</div>
	</body>
</html>