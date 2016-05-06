<html>
	<head>
		<?php
			require_once("funciones.php");
			//Cargamos el idioma
			require_once("../".idioma());
		?>
		<title><?php echo $texto["Mensaje_1"];?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link href="../Estilos/estilo.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<div class="centro" style="width: 80%;">
			<h1><?php echo $texto["Mensaje_2"];?></h1>
			<?php
				//Mostramos el mensaje si hemos recibido uno
				if(isset($_GET["m"]))
				{
					echo $_GET["m"];
				}
				//En caso contrario mostramos un mensaje por defecto
				else
				{
					echo $texto["Mensaje_3"];;
				}	
			?><br>
			<form action="/paginas/portada.php">
				<input type="submit" value="<?php echo $texto["Error_6"]; ?>">
			</form>
		</div>
	</body>
</html>
