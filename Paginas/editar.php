<!DOCTYPE html>  
<?php 
	//Comprobamos que el usuario este logueado y que es administrador
	session_start();
	if (!isset($_SESSION["usuario"]) || !isset($_SESSION["administrador"]) || ($_SESSION["administrador"] != "si"))
	{
		header("Location: /");
	}
	
	//Si no hemos recibido usuario salimos
	if(!isset($_GET["n"]))
	{
		header("Location: ./administracion.php");
	}
	else
	{
		$usuario = $_GET["n"];
	}
	//Cargamos los idiomas.
	require_once("funciones.php");
	require_once("../".idioma());
?>
<html>
	<head>
    	<link href="/Estilos/estilo.css" rel="stylesheet" type="text/css" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<title><?php echo $texto["Editar_1"];?></title>
		<script type="text/javascript">
			//Funcion que nos muestra un aviso cuando marcamos un usuario para ser borrado
			function avisoBorrado()
			{
				if(document.getElementById('borrar').checked)
				{
					alert("<?php echo $texto["Editar_15"];?>");
				}
			}
		</script>
	</head>
    <body onLoad="MM_preloadImages('/Imagenes/boton2.jpg')">
		<!-- div con el logout y los diomas-->
		<div class="cabecera">
    		<?php 
    			cabecera();
    		?>
    	</div>
		
		<!--Contenido-->
    	<div class="contenido" style="text-align: center;">
			<form id="volver" action="./administracion.php" method="post">
				<input type="submit" value="<?php echo $texto["Editar_2"];?>">
			</form>
			<?php
				//Si se ha recibido algun mensaje se muestra
				if(isset($_GET["m"]))
				{
					echo "<p>{$_GET["m"]}</p>";
				}
			?>
			
			<!--Formulario de modificacion de usuario-->
			<h2><?php echo $usuario?></h2>
			<?php
				//Comprobamos que el usuario en cuestion exista
				$sql = "SELECT * FROM usuarios WHERE NOMBRE = '{$usuario}'";
				$conexion = conectarse();
				$res = $conexion->query($sql);
				$tuplas = $res->num_rows;
				
				if($tuplas == 0)
				{
					header("Location: ./administracion.php");
				}
			?>
			<form action="./editando.php" name="editar" id="editar" method="post">
				<label><b><?php echo $texto["Editar_3"];?></b></label><br>
				<input type="password" name="con1" value="" size="20"/><br>
				<label><?php echo $texto["Editar_4"];?></label><br>
				<input type="password" name="con2" value="" size="20"/><br>
				<label><b><?php echo $texto["Editar_5"];?></b></label><br>
				<input type="email" name="correo" value="" maxlength="50" size="50"/><br>
				<label><b><?php echo $texto["Editar_6"];?></b></label><br>
				<?php echo $texto["Editar_7"];?><input type="radio" name="tipo" value="A" size="20"/>
				<?php echo $texto["Editar_8"];?><input type="radio" name="tipo" value="P" size="20"/><br>
				<?php echo $texto["Editar_9"];?><input type="radio" name="tipo" value="G" size="20"/><br>
				<label><b><?php echo $texto["Editar_10"];?></b></label><br>
				<?php echo $texto["Editar_11"];?><input type="radio" name="activa" value="S" size="20"/>
				<?php echo $texto["Editar_12"];?><input type="radio" name="activa" value="N" size="20"/><br>
				<label><b><?php echo $texto["Editar_13"];?></b></label><input type="checkbox" onClick="avisoBorrado();" name="borrar" id="borrar" value="S" size="20"/><br>
				<input type="submit" value="<?php echo $texto["Editar_14"];?>">
				<input type="text" hidden readonly value="<?php echo $usuario; ?>" name="usuario"/>
			</form>
    	</div>
	</body>
</html>