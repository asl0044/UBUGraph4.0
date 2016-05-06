<!DOCTYPE html> 
<html>
	<head>
		<?php
			//Cargamos el paquete de idioma correspondiente
			require_once("/paginas/funciones.php");			
			require_once(idioma());
		?>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	   	<link href="Estilos/estilo.css" rel="stylesheet" type="text/css">
	   	<title><?php echo $texto["Index_1"]; ?></title>
	</head>
	<body>
		<!--Div con la seleccion de idiomas-->
		<div class="centrar" class="idiomas">
			<?php pintarIdiomas(); ?>
		</div>
		
		<div class="centrar" id="titulo">
   			 <h1><?php echo $texto["Index_2"];?></h1>
        </div>
		
		<!-- Div con el formulario de logueo y los botones de registro y recuperacion -->
		<div class="centro">		
			 <form id="form_login" name="login" action="./paginas/login.php" method="post">
	             <h1><b><?php echo $texto["Index_3"]; ?>:</b></h1>
	             <label class="ajustado"><b><?php echo $texto["Index_4"]; ?>:</b></label>
	             <input class="ajustado" required="required" type='text' name='usuario' value='' size=20><br>
	             
	             <label class="ajustado"><b><?php echo $texto["Index_5"]; ?>:</b></label>
	             <input class="ajustado" required="required" type='password' name='con' value='' size=20><br>
	             
	 			 <input class="ajustado" type="submit" value="<?php echo $texto["Index_6"]; ?>">
	        </form>
			<form id="form_registro" action="./paginas/registro.php" method="post">
				<input class="ajustado" type="submit" value="<?php echo $texto["Index_7"]; ?>">
			</form>
			<form id="form_recuperar" action="./paginas/recuperar.php" method="post">
				<input class="ajustado" type="submit" value="<?php echo $texto["Index_8"]; ?>">
			</form>
		</div>
	</body>
</html>



