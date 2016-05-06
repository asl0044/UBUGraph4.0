<?php
	//Destruimos la session
	session_start();
	session_unset();
	session_destroy();

	//Volvemos al index
	header("Location: /");
?>