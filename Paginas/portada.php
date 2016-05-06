<!DOCTYPE html>  
<?php 
	//Comprobamos que el usuario este logueado
	session_start();
	if (!isset($_SESSION["usuario"]))
	{
		header("Location: /");
	}
	require_once ("funciones.php");
?>
<html>
	<head>
    	<link href="/Estilos/estilo.css" rel="stylesheet" type="text/css" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<title>UbuGraph</title>
		
		<script type="text/javascript">
			function ajustar(iframe)
			{
				//Funcion que reajusta el iframe al tamaño de su contenido cada vez que carga
		    	if (iframe)
			    {
			        var iframeWin = iframe.contentWindow || iframe.contentDocument.parentWindow;
			        if (iframeWin.document.body)
			        {
			        	iframe.height = "800px";
			            iframe.height = (iframeWin.document.documentElement.scrollHeight + 1) || (iframeWin.document.body.scrollHeight + 1);
			            //document.getElementById('cargando').style.display='none';
			        }
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
		
		<!--Contenido de la portada-->
    	<div class="contenido">
			<!-- Menu -->
    		<div class="menu">
    			<?php 
    				menu();
    			?>
    		</div>
    		
			<!-- Contenido -->
    		<div class="panelPrincipal">
				<?php
					if(isset($_GET["action"]))
					{
						$action = $_GET["action"];
					}
					else 
					{
						$action = "logo";
					}
					echo "<iframe id=\"iframeContenido\" name=\"iframeContenido\" onload=\"ajustar(this);\" width=\"833px\" height=\"800px\" src=\"./{$action}.php\"></iframe>";
				?>
    		</div>
    	</div>
	</body>
</html>