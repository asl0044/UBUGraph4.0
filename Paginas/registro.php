<!DOCTYPE html> 
<html>
	<head>	
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	   	<link href="../Estilos/estilo.css" rel="stylesheet" type="text/css">
		<?php
			require_once("funciones.php");
			//Cargamos el idioma.
			require_once("../".idioma());
		?>
	    <title><?php echo $texto["Registro_1"]; ?></title>
		<script type="text/javascript">
			//Script que valida que las dos contraseñas sea identicas antes de confirmar el envio del formulario.
			function validar()
			{				
				if (registro.con1.value != registro.con2.value)
				{ 
					alert("<?php echo $texto["Registro_7"]; ?>."); 
					registro.con1.focus(); 
					return false; 
				} 
				return true;
			} 
			
			//Recarga los avisos de disponibilidad al cambiar algun dato sensible del formulario.
			function diponibilidad()
			{ 
				document.getElementById("disponibilidad").src="./disponibilidad.php?id=" + registro.usuario.value + "&correo=" + registro.correo.value;
			}
			
			//Ajusta el tamaño del iframe cuando ha cargado para ajustarse a su contenido
			function ajustar(iframe)
			{
			    if (iframe)
			    {
			        var iframeWin = iframe.contentWindow || iframe.contentDocument.parentWindow;
			        if (iframeWin.document.body)
			        {
			            iframe.height = iframeWin.document.documentElement.scrollHeight || iframeWin.document.body.scrollHeight;
			        }
			    }
			}
		</script> 
	</head>
	<body>
		<!--Div con la seleccion de idiomas-->
		<div class="centrar" class="idiomas">
			<?php pintarIdiomas(); ?>
		</div>
		
		<!-- Div con el formulario de registro -->
		<div class="centro">
			<h1><?php echo $texto["Registro_2"]; ?></h1>
			<form id="registro" action="registro_nuevo.php" method="post">
				<label class="ajustado"><B><?php echo $texto["Registro_3"]; ?>:</B></label>
				<input class="ajustado" type="text" required="required" onInput="diponibilidad()" name="usuario" value="" maxlength="25"/>
				
				<iframe onload="ajustar(this);" width="295" height="75" id="disponibilidad" src="./disponibilidad.php"></iframe> 
				
				<label class="ajustado"><B><?php echo $texto["Registro_4"]; ?>:</B></label>
				<input class="ajustado" type="password" required name="con1" value="" size="20"/>
				
				<label class="ajustado"><B><?php echo $texto["Registro_5"]; ?>:</B></label>
				<input class="ajustado" type="password" required name="con2" value="" size="20"/>
				
				<label class="ajustado"><B><?php echo $texto["Registro_6"] ?>:</B></label>
				<input class="ajustado" onInput="diponibilidad()" type="email" required="required" name="correo" value="" maxlength="50" size="50"/>
				
				<label class="ajustado"><B><?php echo $texto["Registro_9"]; ?>:</B></label>
				<?php echo $texto["Registro_10"]; ?><input type="radio" required name="tipo" value="A" size="20"/>
				<?php echo $texto["Registro_11"]; ?><input type="radio" required name="tipo" value="P" size="20"/>
			
				<input class="ajustado" type="submit" value="<?php echo $texto["Registro_8"]; ?>" onClick="return validar();"/>
			</form>
		</div>
	</body>
</html>