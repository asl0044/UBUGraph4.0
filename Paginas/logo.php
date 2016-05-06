<?php
	//generamos un logo para la web
	require_once ("Image/GraphViz.php");

	$gv = new Image_GraphViz(true, array(), "logo", false, false);
	$gv->setAttributes(array("size"=>"8.333,11.111!"));
	$gv->addNode(' ', array('image' => '../imagenes/logoP.jpg'));
	$gv->addEdge(array('U' => ' '));
	$gv->addEdge(array('B' => ' '));
	$gv->addEdge(array(' U ' => ' '));
	$gv->addEdge(array(' ' => 'G'));
	$gv->addEdge(array(' ' => 'R'));
	$gv->addEdge(array(' ' => 'A'));
	$gv->addEdge(array(' ' => 'P'));
	$gv->addEdge(array(' ' => 'H'));
	$gv->image();
?>