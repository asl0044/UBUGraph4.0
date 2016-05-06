<!DOCTYPE HTML>
<?php
	session_start();
	if (!isset($_SESSION["usuario"]))
	{ 
		header("Location: /");
	}
	require_once("funciones.php");
	//Cargamos el idioma
	require_once("../".idioma());
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" 	href="/estilos/estilo.css">
		<?php
			//Variables necearias para establecesr los nodos y las precedencias
			$nombres = null;
			$precedencias = null;
			$duraciones = null;
			
			//Comprobamos si se han recibido datos nuevos del formulario
			if(isset($_POST["nombre"]))
			{
				//comprobamos si se han recibido datos antiguos del formulario
				//Si tenemos datos antiguos los cargamos
				if (isset($_POST["nombres"]))
				{
					$nombres = $_POST["nombres"];
					$precedencias = $_POST["precedencias"];
					$duraciones = $_POST["duraciones"];
				}
				//Si no tenemos datos antiguos, los inicializamos a vacio.
				else
				{
					$nombres = array();
					$precedencias = array();
					$duraciones = array();
				}
				
				//Introducimos el nuevo dato junto a los antiguos
				//Nombre
				array_push($nombres, $_POST["nombre"]);
				
				//Precedencias en caso de haberlas.
				if (isset($_POST["precedencia"]))
				{
					$p = "";
					foreach($_POST["precedencia"] as $value)
					{
						if($p == "")
						{
							$p = $value;
						}
						else
						{
							$p = $p." ".$value;
						}
					}
					array_push($precedencias, $p);
				}
				else
				{
					array_push($precedencias, null);
				}
				//Duracion
				array_push($duraciones, $_POST["duracion"]);
			}
		?>
	</head>
    <body>	
        <div id="main" style="text-align: center;">
			<!-- formulario de agregacion de nodos nuevos -->
			<form id="proponer_problema" action="./proponer.php" method="post">
				<label><b><?php echo $texto["Proponer_1"];?></b></label>
				<input name="nombre" pattern="<?php if($nombres != null){foreach($nombres as $n){echo "(?!^{$n}$)";}}?>(?!^F[0-9]+$)[^ ]+" type="text" required maxlength="25" title="<?php echo $texto["Proponer_2"];?>" size="25"/><br>
				
				<?php
					//En caso de que existan nodos previos, los agregamos a la lista de posibles precedencias.
					if($nombres != null)
					{
					echo "\n<label><b>{$texto["Proponer_3"]} </b></label>";
						$escrito = false;
						foreach($nombres as $value)
						{
							if($escrito)
							{
								echo ", ";
							}
							echo "\n{$value}:<input name=\"precedencia[]\" type=\"checkbox\" value=\"{$value}\"/>";
							$escrito = true;
						}
						echo "\n<br>";
					}
				?>
				
				<label><b><?php echo $texto["Proponer_4"];?></b></label>
				<input name="duracion" type="number" required min="1" size="25" step="1"/><br>
				
				<input type="submit" value="<?php echo $texto["Proponer_5"];?>">
				
				<?php
					//Si existen datos antiguos, los mostramos en una tabla.
					if($nombres != null)
					{
						echo "\n<h2 style=\"text-align: center;\">{$texto["Proponer_6"]}</h2>";
						echo "\n<table style=\"width: 100%;\">";
							echo "\n<tr>";
								echo "\n<th>{$texto["Proponer_7"]}</th>";
								echo "\n<th>{$texto["Proponer_8"]}</th>";
								echo "\n<th>{$texto["Proponer_9"]}</th>";
							echo "\n</tr>";
							for($i = 0; $i < sizeof($nombres); $i++)
							{
								echo "\n<tr>";
									echo "\n<td>{$nombres[$i]}</td>";
									echo "\n<td>{$precedencias[$i]}</td>";
									echo "\n<td>{$duraciones[$i]}</td>";
								echo "\n</tr>";
							}
						echo "\n</table><br>";
						//Botones para resolver los grafos en los dos posibles formatos.
						echo "\n<input type=\"submit\" formnovalidate formaction=\"./roy.php\" value=\"{$texto["Proponer_10"]}\"/> ";
						echo "\n<input type=\"submit\" formnovalidate formaction=\"./pertCorregido.php\" value=\"{$texto["Proponer_11"]}\"/>";
					}
					
					//escribimos los datos antiguos en campos ocultos para que se envien en la siguiente iteracion o en la resolucion del problema.
					if($nombres != null)
					{
						for($i = 0; $i < sizeof($nombres); $i++)
						{
							echo "\n<input hidden name=\"nombres[]\" value=\"{$nombres[$i]}\"/>";
							echo "\n<input hidden name=\"precedencias[]\" value=\"{$precedencias[$i]}\"/>";
							echo "\n<input hidden name=\"duraciones[]\" value=\"{$duraciones[$i]}\"/>";
						}
					}
				?>
			</form>
        </div>
	</body>
</html>