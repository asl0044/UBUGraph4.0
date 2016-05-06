<html>
	<head>
		<?php 
			require_once("funciones.php");
			//Cargamos el idioma correcto
			require_once("../".idioma());
		?>
		<title><?php echo $texto["Error_1"];?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link href="../Estilos/estilo.css" rel="stylesheet" type="text/css">		
	</head>
	<body>
		<div class="centro" style="width: 80%;">
			<h1><?php echo $texto["Error_2"]; ?></h1>
			<?php
				//Mostramos el error que hemos recibido
				if(isset($_GET["e"]))
				{
					echo "{$texto["Error_3"]}<br>";
					echo $_GET["e"];
				}
				//En caso de no haber recibido ningun error mostramos uno por defecto.
				else
				{
					echo $texto["Error_4"];
				}
				
				echo "<br>{$texto["Error_5"]}<br>";
			?><br>
			<form action="/paginas/portada.php">
				<input type="submit" value="<?php echo $texto["Error_6"]; ?>">
			</form>
		</div>
		
	</body>
</html>
