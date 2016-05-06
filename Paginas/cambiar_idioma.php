<?php
	//Acualzia la variable de sesion al idioma correspondiente en caso de haberlo
	if(isset($_POST["idioma"]))
	{
		session_start();	
		$_SESSION["idioma"] = $_POST["idioma"];
		//Volvemos donde estabamos
		header("location: ".$_POST["link"]);	
	}
	else
	{
		header("location: /");
	}
?>