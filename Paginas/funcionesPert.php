<?php 
	require_once("Image/GraphViz.php");
	
	require_once("./Actividad.php");
	
	function generarActividades($nombres,&$precedencias,$duraciones){
		$grafo = array();
		for($i = 0; $i < count($nombres); $i++)
		{
			$grafo[$nombres[$i]] = new Actividad($nombres[$i], $duraciones[$i]);
			$precedencias[$i] = explode(" ", $precedencias[$i]);			
			foreach($precedencias[$i] as $value)
			{
				if($value != "")
				{
					$grafo[$nombres[$i]]->addActividadPrecedente($value);
				}
			}
		}
		return $grafo;
	}
	
	function establecerPrecedenciasPert(&$grafo,$nombres, $duraciones, $precedencias){
		foreach($grafo as $value)
		{
			foreach($value->getPrecedentes() as $p)
			{
				$grafo[$p]->addActividadPosterior($value->getID());
			}
		}
		//Establecemos los nodos de inicio y fin
		//Primero para los nodos que no tienen precedentes los de inicio
		foreach($grafo as $value)
		{
			if(count($value->getPrecedentes()) == 0)
			{
				$value->establecerNodoOrigen(1);
			}
		}
											
		$siguienteNodo = 2;
		
		//Despues los de inicio para todos los demás y de fin para los que tienen posteriores
		do
		{
			$continuar = false;
			foreach($grafo as $value)
			{
				if($value->getNodoOrigen() == -1)
				{
					$procesados = true;
					$todosDestino = true;
					$destinoMax = -1;
					
					foreach($value->getPrecedentes() as $p)
					{
						if($grafo[$p]->getNodoOrigen() == -1)
						{
							$procesados = false;
							$continuar = true;
						}
						
						if($grafo[$p]->getNodoDestino() == -1)
						{
							$todosDestino = false;
						}
					}
					
					if($procesados)
					{
						if($todosDestino)
						{
							$destinoMax = -1;				
							foreach($value->getPrecedentes() as $p)
							{
								if($destinoMax < $grafo[$p]->getNodoDestino())
								{
									$destinoMax = $grafo[$p]->getNodoDestino();
								}
							}
								
							$value->establecerNodoOrigen($destinoMax);
						}
						else
						{
							$value->establecerNodoOrigen($siguienteNodo);
							
							foreach($value->getPrecedentes() as $p)
							{
								if($grafo[$p]->getNodoDestino() == -1)
								{
									$grafo[$p]->establecerNodoDestino($siguienteNodo);
								}
							}
							
							$siguienteNodo++;
						}
					}
				}
			}	
		}
		while ($continuar);
		
		foreach($grafo as $value)
		{
			if($value->getNodoDestino() == -1)
			{
				$value->establecerNodoDestino($siguienteNodo);
			}
		}
	}
	
	function establecerFicticias(&$grafo,$nombres, $duraciones, $precedencias,$precedenciasRoy){
		//Ahora ya podemos calcular las ficticias.
		$nFicticias = 1;
		
		//Caso 2 -> Todas las actividades posteriores de una actividad están contenidas en las actividades posteriores de otra actividad.
		foreach($grafo as $value)
		{
			foreach($grafo as $value2)
			{
				if($value->getID() != $value2->getID())
				{
					
					$problematica = true;
					
					if((count($value->getPosteriores()) == 0) || (count($value2->getPosteriores()) == 0))
					{
						$problematica = false;
					}
					
					if($value->getPosteriores() == $value2->getPosteriores())
					{
						$problematica = false;
					}
					
					foreach($value2->getPosteriores() as $p)
					{
						if(!in_array($p, $value->getPosteriores()))
						{
							$problematica = false;
						}
					}
					
					if($problematica)
					{
						$hecho = false;
						foreach($grafo as $value3)
						{
							if(($value3->getNodoOrigen() == $value->getNodoDestino()) && ($value3->getNodoDestino() == $value2->getNodoDestino()))
							{
								$hecho = true;
							}
						}
						if(!$hecho)
						{
							if($value->getNodoDestino() == $value2->getNodoDestino())
							{
								foreach($grafo as $value3)
								{
									if($value->getID() != $value3->getID())
									{
										if($value->getNodoDestino() <= $value3->getNodoOrigen())
										{
											$value3->establecerNodoOrigen($value3->getNodoOrigen() + 1);
										}
										if($value->getNodoDestino() <= $value3->getNodoDestino())
										{
											$value3->establecerNodoDestino($value3->getNodoDestino() + 1);
										}
									}
								}
								
								foreach($value->getPosteriores() as $p)
								{
									if(!in_array($p, $value2->getPosteriores()))
									{
										if($grafo[$p]->getNodoOrigen() == $value2->getNodoDestino())
										{
											$grafo[$p]->establecerNodoOrigen($value->getNodoDestino());
										}
									}
								}
							}
							$grafo["F".$nFicticias] = new Actividad("F".$nFicticias, 0);
							$grafo["F".$nFicticias]->establecerNodoOrigen($value->getNodoDestino());
							$grafo["F".$nFicticias]->establecerNodoDestino($value2->getNodoDestino());
							//Añado la actividad precedente (Adrián)
							$grafo["F".$nFicticias]->addActividadPrecedente($value->getID());
							$grafo["F".$nFicticias]->setFicticia();
							$nFicticias++;
						}
					}
				}
			}
		}
		//Caso 3 -> Dos actividades diferentes comparten parcialmente sus actividades posteriores
		foreach($grafo as $value)
		{
			foreach($grafo as $value2)
			{
				if($value->getID() != $value2->getID())
				{
					
					$problematica = true;
					
					if((count($value->getPosteriores()) == 0) || (count($value2->getPosteriores()) == 0))
					{
						$problematica = false;
					}
					
					if($value->getPosteriores() == $value2->getPosteriores())
					{
						$problematica = false;
					}
					
					$comparten = false;
					$noComparten = false;
					foreach($value->getPosteriores() as $p)
					{
						if(in_array($p, $value2->getPosteriores()))
						{
							$comparten = true;
						}
						else
						{
							$noComparten = true;
						}
					}
					
					if(!($comparten && $noComparten))
					{
						$problematica = false;
					}
					
					$comparten = false;
					$noComparten = false;
					foreach($value2->getPosteriores() as $p)
					{
						if(in_array($p, $value->getPosteriores()))
						{
							$comparten = true;
						}
						else
						{
							$noComparten = true;
						}
					}
					
					if(!($comparten && $noComparten))
					{
						$problematica = false;
					}
					
					if($problematica)
					{//print_r($value);print_r($value2);
						$hecho = true;
						foreach($value->getPosteriores() as $p)
						{
							if(in_array($p, $value2->getPosteriores()))
							{
								if(($grafo[$p]->getNodoOrigen() == $value->getNodoDestino()) || ($grafo[$p]->getNodoOrigen() == $value2->getNodoDestino()))
								{
									$hecho = false;
								}
							}
						}
						
						foreach($value2->getPosteriores() as $p)
						{
							if(in_array($p, $value->getPosteriores()))
							{
								if(($grafo[$p]->getNodoOrigen() == $value->getNodoDestino()) || ($grafo[$p]->getNodoOrigen() == $value2->getNodoDestino()))
								{
									$hecho = false;
								}
							}
						}
						if(!$hecho)
						{
							if($value->getNodoDestino() == $value2->getNodoDestino())
							{
								$original = $value->getNodoDestino();
								
								foreach($value->getPosteriores() as $p)
								{
									if($grafo[$p]->getNodoOrigen() == $original)
									{
										$grafo[$p]->establecerNodoOrigen(-1);
									}
								}
								
								foreach($value2->getPosteriores() as $p)
								{
									if($grafo[$p]->getNodoOrigen() == $original)
									{
										$grafo[$p]->establecerNodoOrigen(-1);
									}
								}
								
								foreach($grafo as $value3)
								{
									if($value3->getNodoOrigen() > $original)
									{
										$value3->establecerNodoOrigen($value3->getNodoOrigen() + 2);
									}
									if($value3->getNodoDestino() > $original)
									{
										$value3->establecerNodoDestino($value3->getNodoDestino() + 2);
									}
								}
								
								$value->establecerNodoDestino($original);
								$value2->establecerNodoDestino($original + 1);
								
								$grafo["F".$nFicticias] = new Actividad("F".$nFicticias, 0);
								$grafo["F".$nFicticias]->establecerNodoOrigen($value->getNodoDestino());
								//Añado la actividad precedente (Adrián)
								$grafo["F".$nFicticias]->addActividadPrecedente($value->getID());
								$grafo["F".$nFicticias]->establecerNodoDestino($original + 2);
								$grafo["F".$nFicticias]->setFicticia();
								$nFicticias++;
								
								$grafo["F".$nFicticias] = new Actividad("F".$nFicticias, 0);
								$grafo["F".$nFicticias]->establecerNodoOrigen($value2->getNodoDestino());
								//Añado la actividad precedente (Adrián)
								$grafo["F".$nFicticias]->addActividadPrecedente($value2->getID());
								$grafo["F".$nFicticias]->establecerNodoDestino($original + 2);
								$grafo["F".$nFicticias]->setFicticia();
								$nFicticias++;
								
								foreach($grafo as $value3)
								{
									if($value3->getNodoOrigen() == -1)
									{
										if((in_array($value->getID(), $value3->getPrecedentes())) && (in_array($value2->getID(), $value3->getPrecedentes())))
										{
											$value3->establecerNodoOrigen($original + 2);
										}
										else if(in_array($value->getID(), $value3->getPrecedentes()))
										{
											$value3->establecerNodoOrigen($value->getNodoDestino());
										}
										else
										{
											$value3->establecerNodoOrigen($value2->getNodoDestino());
										}
									}
								}
							}
							else
							{
								$maximo = max($value->getNodoDestino(), $value2->getNodoDestino());
								
								foreach($grafo as $value3)
								{
									if(($value->getNodoDestino() == $value3->getNodoOrigen()) || ($value2->getNodoDestino() == $value3->getNodoOrigen()))
									{
										if((in_array($value3->getID(), $value->getPosteriores())) || (in_array($value3->getID(), $value2->getPosteriores())))
										{
											$value3->establecerNodoOrigen(-1);
										}
									}
								}
								
								foreach($grafo as $value3)
								{
									if($value3->getNodoOrigen() > $maximo)
									{
										$value3->establecerNodoOrigen($value3->getNodoOrigen() + 1);
									}
									if($value3->getNodoDestino() > $maximo)
									{
										$value3->establecerNodoDestino($value3->getNodoDestino() + 1);
									}
								}
								
								$grafo["F".$nFicticias] = new Actividad("F".$nFicticias, 0);
								$grafo["F".$nFicticias]->establecerNodoOrigen($value->getNodoDestino());
								//Añado la actividad precedente (Adrián)
								$grafo["F".$nFicticias]->addActividadPrecedente($value->getID());
								$grafo["F".$nFicticias]->establecerNodoDestino($maximo + 1);
								$grafo["F".$nFicticias]->setFicticia();
								$nFicticias++;
								
								$grafo["F".$nFicticias] = new Actividad("F".$nFicticias, 0);
								$grafo["F".$nFicticias]->establecerNodoOrigen($value2->getNodoDestino());
								//Añado la actividad precedente (Adrián)
								$grafo["F".$nFicticias]->addActividadPrecedente($value2->getID());
								$grafo["F".$nFicticias]->establecerNodoDestino($maximo + 1);
								$grafo["F".$nFicticias]->setFicticia();
								$nFicticias++;
								
								foreach($grafo as $value3)
								{
									if($value3->getNodoOrigen() == -1)
									{
										if((in_array($value->getID(), $value3->getPrecedentes())) && (in_array($value2->getID(), $value3->getPrecedentes())))
										{
											$value3->establecerNodoOrigen($maximo + 1);
										}
										else if(in_array($value->getID(), $value3->getPrecedentes()))
										{
											$value3->establecerNodoOrigen($value->getNodoDestino());
										}
										else
										{
											$value3->establecerNodoOrigen($value2->getNodoDestino());
										}
									}
								}
							}
						}
					}
				}
			}
		}
		//Caso 1 -> origen y destino iguales entre dos actividades
		foreach($grafo as $value)
		{
			foreach($grafo as $value2)
			{
				if(($value->getNodoOrigen() == $value2->getNodoOrigen()) && ($value->getNodoDestino() == $value2->getNodoDestino()) && ($value->getID() != $value2->getID()))
				{
					$cambio = $value2->getNodoOrigen();
					
					foreach($grafo as $c)
					{
						if($c->getNodoOrigen() > $cambio)
						{
							$c->establecerNodoOrigen($c->getNodoOrigen() + 1);
						}
						
						if($c->getNodoDestino() > $cambio)
						{
							$c->establecerNodoDestino($c->getNodoDestino() + 1);
						}
					}
					
					$value->establecerNodoOrigen($cambio);
					$value2->establecerNodoOrigen($cambio + 1);
					
					$grafo["F".$nFicticias] = new Actividad("F".$nFicticias, 0);
					$grafo["F".$nFicticias]->establecerNodoOrigen($cambio);
					$grafo["F".$nFicticias]->establecerNodoDestino($cambio + 1);
					//Añado la actividad precedente (Adrián)
					$grafo["F".$nFicticias]->addActividadPrecedente($value2->getID());
					$grafo["F".$nFicticias]->setFicticia();
					$nFicticias++;
				}
			}
		}
		
		$nodos = array();
		
		foreach($grafo as $value)
		{
			if(!isset($nodos[$value->getNodoOrigen()]))
			{
				$nodos[$value->getNodoOrigen()] = array("tei" => 0, "tli" => 00);
			}
			
			if(!isset($nodos[$value->getNodoDestino()]))
			{
				$nodos[$value->getNodoDestino()] = array("tei" => 0, "tli" => 00);
			}
		}
		
		for($i = 2; $i <= count($nodos); $i++)
		{
			foreach($grafo as $value)
			{
				if($value->getNodoDestino() == $i)
				{
					$nodos[$i]["tei"] = max($nodos[$i]["tei"], $nodos[$value->getNodoOrigen()]["tei"] + $value->getDuracion());
				}
			}
		}
		
		for($i = 1; $i <= count($nodos); $i++)
		{
			$nodos[$i]["tli"] = $nodos[count($nodos)]["tei"];
		}
		
		for($i = count($nodos); $i > 1; $i--)
		{
			foreach($grafo as $value)
			{
				if($value->getNodoOrigen() == $i)
				{
					$nodos[$i]["tli"] = min($nodos[$i]["tli"], $nodos[$value->getNodoDestino()]["tei"] - $value->getDuracion());
				}
			}
		}	
		
		//Comprobamos los nodos comparando con el grafo ROY (Adrián)
		//Recorremos cada una de las actividades del grafo(Adrián)

		$grafoRoy = generarRoy($nombres, $precedenciasRoy, $duraciones);
		foreach($grafo as $value)
		{
			if(!$value->getFicticia()){
				$nodoRoy = $grafoRoy[$value->getID()];
				$nodos[$value->getNodoDestino()]["tei"] = $nodoRoy->getTEI() + $nodoRoy->getDuracion();
				$nodos[$value->getNodoDestino()]["tli"] = $nodoRoy->getTLI() + $nodoRoy->getDuracion();
			}
			//Comparamos una actividad con el resto (Adrián)
			foreach($grafo as $value2){
				if($value != $value2){
					if($value->getNodoDestino() == $value2->getNodoDestino()){
						if(!$value->getFicticia() and !$value2->getFicticia()){
							$tei1 = $grafoRoy[$value->getID()]->getTEI() + $grafoRoy[$value->getID()]->getDuracion();
							$tli1 = $grafoRoy[$value->getID()]->getTLI() + $grafoRoy[$value->getID()]->getDuracion();
							$tei2 = $grafoRoy[$value2->getID()]->getTEI() + $grafoRoy[$value2->getID()]->getDuracion();
							$tli2 = $grafoRoy[$value2->getID()]->getTLI() + $grafoRoy[$value2->getID()]->getDuracion();
						
							if($nodos[$value->getNodoDestino()]["tei"] < max($tei1,$tei2))
								$nodos[$value->getNodoDestino()]["tei"] = max($tei1,$tei2);
							if($nodos[$value->getNodoDestino()]["tli"] > min($tli1,$tli2))
								$nodos[$value->getNodoDestino()]["tli"] = min($tli1,$tli2);
						}
					}							
				}
			}				
		}
						
		//Comprobación de ficticias (Adrian)
		foreach($grafo as $value){
			$precedentes = array();
			if($value->getFicticia()){
				foreach($grafo as $value2){
					if($value->getNodoDestino() == $value2->getNodoDestino()){
						$precedentes[$value->getID()] = $nodos[$value->getNodoOrigen()]["tei"] + $value->getDuracion();
					}
				}							
			}else
				continue;
			$nodos[$value->getNodoDestino()]["tei"] = max($precedentes);
		}
		return $nodos;
	}
	
	function generarRoy($nombres, $precedenciasRoy,$duraciones){
		require_once ("funcionesRoy.php");
		$grafoRoy = generarNodos($nombres,$precedenciasRoy,$duraciones);
		establecerPrecedenciasRoy($grafoRoy,$nombres,$duraciones,$precedenciasRoy);
		calcularTiempos($grafoRoy);
		return $grafoRoy;
	}
	
	function generarGrafoPert($grafo,$nodos,$resolver = false,$conexion = null,$preguntas = null) {
		$gv = new Image_GraphViz(true, array("rankdir"=>"LR", "size"=>"8.333,11.111!"), "PERT", false, false);
		//Añadimos los nodos al grafo
		for($i = 1; $i <= count($nodos); $i++)
		{
			$gv->addNode($i, array("label"=>"({$nodos[$i]["tei"]}){$i}({$nodos[$i]["tli"]})"));
			
			//Si es necesario obtenemos la respuesta a la pregunta 4
			if($resolver){
				if($i == count($nodos))
				{
					$respuesta4 = $nodos[$i]["tei"];
				}
			}
		}
		
		$respuesta5 = "";
		//Añadimos los arcos
		foreach($grafo as $value)
		{
			if($value->getFicticia())
			{
				$gv->addEdge(array($value->getNodoOrigen() => $value->getNodoDestino()), array("label" => $value->getID()."(".$value->getDuracion().")", "style" => "dashed"));
			}
			else
			{
				$color = "black";
				$holgura = $nodos[$value->getNodoDestino()]["tli"] - $nodos[$value->getNodoOrigen()]["tei"] - $value->getDuracion();
				
				if($holgura == 0)
				{
					$color = "red";
					
					//Si es necesario obtenemos la respuesta a la pregunta 5
					if(($respuesta5 != "") && $resolver)
					{
						$respuesta5 = $respuesta5.",";
					}
					if($resolver)
					{
						$respuesta5 = $respuesta5.$value->getID();
					}
				}
				
				//Si es necesario obtenemos la respuesta a las pregunta 1 2 3
				if($resolver)
				{
					if($value->getID() == $preguntas["NOMBRE_1"])
					{
						$respuesta1 = $holgura;
					}
					
					if($value->getID() == $preguntas["NOMBRE_2"])
					{
						$respuesta2 = $nodos[$value->getNodoOrigen()]["tei"];
					}
					
					if($value->getID() == $preguntas["NOMBRE_3"])
					{
						$respuesta3 = $nodos[$value->getNodoDestino()]["tli"];
					}
				}
				
				$gv->addEdge(array($value->getNodoOrigen() => $value->getNodoDestino()), array("color" => $color, "label" => $value->getID()."(".$value->getDuracion().")[>{$holgura}<]"));
			}
		}
			//Si es necesario guardamos las respuestas correctas en la BD
		if($resolver)
		{
			$consulta = "INSERT INTO respuestas_correctas(ID_GRAFO, RESPUESTA_1, RESPUESTA_2, RESPUESTA_3, RESPUESTA_4, RESPUESTA_5) VALUES({$preguntas["ID_GRAFO"]}, {$respuesta1}, {$respuesta2}, {$respuesta3}, {$respuesta4}, '{$respuesta5}');";
			$conexion->query($consulta);
		}
		return $gv;
	}
?>
	
	