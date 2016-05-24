<?php	
	/**
	 * Se conecta a la base de datos.
	 * @author Ruben Arranz
	 * @return mysqli
	 */
	function conectarse() //inicia una conexion con la BD
	{
		$conexion = new mysqli('localhost','UBUGraph','nscYGmDGB2Pd987f','ubugraph'); //REALIZA LA CONEXION
		return $conexion;
	}
	
	/**
	 * Crea un menu lateral
	 * @author Ruben Arranz
	 */
	function menu()
	{
		require("../".idioma());
		//texto a mostrar en cada boton
		$titulos = array(0=>"", 1=>"", 2=>"", 3=>"");
		$titulos[0] = $texto["Funciones_menu_1"];
		$titulos[1] = $texto["Funciones_menu_2"];
		$titulos[2] = $texto["Funciones_menu_3"];
		$titulos[3] = $texto["Funciones_menu_4"];
		$titulos[4] = $texto["Funciones_menu_5"];
		
		//Redireccion de cada boton
		$enlaces = array(0=>"generar", 1=>"proponer", 2=>"historico", 3=>"tutoria", 4=>"generarXML");
		
		for($i = 0; $i < count ($titulos); $i++) //Mientras queden campos en el array crea un nuevo boton
		{
			echo "\n<a href=\"?action=".$enlaces[$i]."\">";
			echo "\n<div class=\"botonMenu\">";			
			echo "\n<BR/>".$titulos[$i];
			echo "\n</div>";
			echo "\n</a>";
		}
	}
	
	/**
	 * Crea la cabecera con el loggout y la seleccion de idiomas
	 * @author Ruben Arranz
	 */
	function cabecera()
	{
		require("../".idioma());
		$usuario = $_SESSION["usuario"];
		
		echo "\n<div>";
			pintarIdiomas();
			echo "\n<br>{$texto["Funciones_cabecera_1"]} {$usuario}.  <a href=\"/paginas/logout.php\">Logout</a>";
		echo "\n</div>";
	}
	
	/**
	 * funcion que dibuja el menu de seleccion de idiomas y la ayuda
	 * @author Ruben Arranz
	 */
	function pintarIdiomas()
	{
		$punto = "";
		//obtenemos la ruta actual para establecer la redireccion correcta
		if(substr(getcwd(), -7) == "Paginas")
		{
			$punto = ".";
		}
		
		
		//Mostramos un enlace a la ayuda
		require("{$punto}./paginas/confg.php");
		echo "<a href=\"{$cfg["WIKI"]}\" target=\"_blank\"><img src=\"/imagenes/ayuda.png\" style=\"height: 25px; width: 25px;\"/></a><br>";
		
		//Estaneamos los paquetes de idiomas instalados en el directorio correspondiente
		foreach (scandir("{$punto}./Idiomas/") as $valor)
		{
			//Omitimos los ficheros arriba, actual e index.
			if(($valor != ".") && ($valor != "..") && ($valor != "index.php"))
			{
				//Generamos el formulario correspondiente de cambio de idioma.
				echo "<form style=\"display: inline-block;\" method=\"post\" action=\"/paginas/cambiar_idioma.php\"/>";
				echo "<input type=\"text\" name=\"idioma\" value=\"".str_replace(".php", "", $valor)."\" hidden/>";
				echo "<input type=\"text\" name=\"link\" value=\"".$_SERVER['REQUEST_URI']."\" hidden/>";
				echo "<input type=\"image\" alt=\"".str_replace(".php", "", $valor)."\" title=\"".str_replace(".php", "", $valor)."\" src=\"/Imagenes/Banderas/".str_replace(".php", "", $valor).".png\"/>";
				echo "</form> ";
			}
		}
	}
	
	/**
	 * Devuelve el idioma seleccionado actualmente o el catellano por defecto
	 * @author Ruben Arranz
	 */
	function idioma()
	{
		//Arrancamos sesion si no lo esta ya
		if (session_id() == '')
		{
			session_start();
		}
		
		//Obtenemos el idioma actual de la session
		if(isset($_SESSION["idioma"]))
		{
			return "/Idiomas/".$_SESSION["idioma"].".php";
		}
		//Si no hay idioma seleccionado, devolvemos castellano por defecto
		return "/Idiomas/Castellano.php";
	}
	
	/**
	 * Muestra la evaluacion de un grafo y la guarda en la BD si es necesario
	 * @author Ruben Arranz
	 */
	function evaluar($idGrafo)
	{
		require("../".idioma());
		
		$conexion = conectarse();
		
		//Obtenemos los datos almacenados en la BD para el grafo indicado
		$consulta = "SELECT * FROM preguntas WHERE ID_GRAFO = {$idGrafo}";
		$result = $conexion->query($consulta);
		$preguntas = $result->fetch_assoc();
		
		$consulta = "SELECT * FROM respuestas WHERE ID_GRAFO = {$idGrafo}";
		$result = $conexion->query($consulta);
		$respuestas = $result->fetch_assoc();
		
		$consulta = "SELECT * FROM respuestas_correctas WHERE ID_GRAFO = {$idGrafo}";
		$result = $conexion->query($consulta);
		$correcciones = $result->fetch_assoc();
		
		$calificacion = 0;
		
		//Mostramos las preguntas, las respuestas dadas, las correctas y el total de aciertos
		echo "\n<h2 style=\"text-align: center;\">{$texto["Funciones_evaluar_1"]}</h2>";
		
		echo "\n<p>{$texto["Funciones_evaluar_2"]} {$preguntas["NOMBRE_1"]}?</p>";
		echo "\n<ul><li>{$texto["Funciones_evaluar_3"]} {$correcciones["RESPUESTA_1"]}</li>";
		echo "\n<li>{$texto["Funciones_evaluar_4"]} {$respuestas["RESPUESTA_1"]}";		
		if($correcciones["RESPUESTA_1"] == $respuestas["RESPUESTA_1"])
		{
			$calificacion++;
			echo  "<img class=\"logo\" src=\"../imagenes/bien.png\" alt=\"bien\">";
		}
		else
		{
			echo  "<img class=\"logo\" src=\"../imagenes/mal.png\" alt=\"mal\">";
		}
		echo "</li></ul>";
		
		echo "\n<p>{$texto["Funciones_evaluar_5"]} {$preguntas["NOMBRE_2"]}?</p>";
		echo "\n<ul><li>{$texto["Funciones_evaluar_3"]} {$correcciones["RESPUESTA_2"]}</li>";
		echo "\n<li>{$texto["Funciones_evaluar_4"]} {$respuestas["RESPUESTA_2"]}";
		if($correcciones["RESPUESTA_2"] == $respuestas["RESPUESTA_2"])
		{
			$calificacion++;
			echo  "<img class=\"logo\" src=\"../imagenes/bien.png\" alt=\"bien\">";
		}
		else
		{
			echo  "<img class=\"logo\" src=\"../imagenes/mal.png\" alt=\"mal\">";
		}
		echo "</li></ul>";
		
		echo "\n<p>{$texto["Funciones_evaluar_6"]} {$preguntas["NOMBRE_3"]}?</p>";
		echo "\n<ul><li>{$texto["Funciones_evaluar_3"]} {$correcciones["RESPUESTA_3"]}</li>";
		echo "\n<li>{$texto["Funciones_evaluar_4"]} {$respuestas["RESPUESTA_3"]}";
		if($correcciones["RESPUESTA_3"] == $respuestas["RESPUESTA_3"])
		{
			$calificacion++;
			echo  "<img class=\"logo\" src=\"../imagenes/bien.png\" alt=\"bien\">";
		}
		else
		{
			echo  "<img class=\"logo\" src=\"../imagenes/mal.png\" alt=\"mal\">";
		}
		echo "</li></ul>";
		
		echo "\n<p>{$texto["Funciones_evaluar_7"]}</p>";
		echo "\n<ul><li>{$texto["Funciones_evaluar_3"]} {$correcciones["RESPUESTA_4"]}</li>";
		echo "\n<li>{$texto["Funciones_evaluar_4"]} {$respuestas["RESPUESTA_4"]}";
		if($correcciones["RESPUESTA_4"] == $respuestas["RESPUESTA_4"])
		{
			$calificacion++;
			echo  "<img class=\"logo\" src=\"../imagenes/bien.png\" alt=\"bien\">";
		}
		else
		{
			echo  "<img class=\"logo\" src=\"../imagenes/mal.png\" alt=\"mal\">";
		}
		echo "</li></ul>";
		
		echo "\n<p>{$texto["Funciones_evaluar_8"]}</p>";
		echo "\n<ul><li>{$texto["Funciones_evaluar_3"]} {$correcciones["RESPUESTA_5"]}</li>";
		echo "\n<li>{$texto["Funciones_evaluar_4"]} {$respuestas["RESPUESTA_5"]}";
		
		$a = explode(",",$respuestas["RESPUESTA_5"]);
		$b = explode(",",$correcciones["RESPUESTA_5"]);
		
		if(count(array_diff($b, $a)) == 0)
		{
			$calificacion++;
			echo  "<img class=\"logo\" src=\"../imagenes/bien.png\" alt=\"bien\">";
		}
		else
		{
			echo  "<img class=\"logo\" src=\"../imagenes/mal.png\" alt=\"mal\">";
		}
		echo "</li></ul>";
		
		echo "\n<h2 style=\"text-align: center;\">{$texto["Funciones_evaluar_9"]} {$calificacion}/5</h2>";
		
		//Comprobamos si es necesario guardar la calificacion en la bd
		$consulta = "SELECT * FROM grafos WHERE CALIFICACION IS NULL AND ID_GRAFO = {$idGrafo};";
		$result = $conexion->query($consulta);
		$tuplas = $result->num_rows;
		if($tuplas != 0)
		{
			$consulta = "UPDATE grafos SET CALIFICACION = {$calificacion} WHERE ID_GRAFO = {$idGrafo};";
			$conexion->query($consulta);
		}
		
		mysqli_close($conexion);
	}
	
	/**
	 * Clase que encripta y desencripta cadenas de texto
	 */
	class Encrypter 
	{
		 private static $Key = "9Gas7tezsNNJXFJW9XdVX7xTf6vQeqh3";
		
		/**
		 * Encripta una cadena
		 * @param Cadena que queremos encriptar
		 * @return Cadena encriptada
		 */
		public static function encrypt ($input)
		{
			$output = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(Encrypter::$Key), $input, MCRYPT_MODE_CBC, md5(md5(Encrypter::$Key))));
			return $output;
		}
		
		/**
		 * Desencripta una cadena
		 * @param Cadena que queremos desencriptar
		 * @return Cadena desencriptada
		 */
		public static function decrypt ($input)
		{			
			$output = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(Encrypter::$Key), base64_decode($input), MCRYPT_MODE_CBC, md5(md5(Encrypter::$Key))), "\0");
			return $output;
		}
	}
?>