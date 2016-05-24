<?php
	/**
	 * Clase que implementa el nodo de un grafo.
	 * @author Ruben Arranz Alonso de L.
	 */	
	class Nodo 
	{
		//////ATRIBUTOS//////
		
		
		/**
		 * La duracion de la tarea que representa el nodo.
		 */
		private $duracion;
		
		/**
		 * El ID del nodo, también es el nombre que se mostrara.
		 */
		private $id;
		
		/**
		 * Los IDs de los nodos que preceden al nuestro
		 */
		private $nodosPrecedentes = array();
		
		/**
		 * Los IDs de los nodos que los que este es precedente
		 */
		private $nodosPosteriores = array();
		
		/**
		 * Tiempo early de fin
		 */
		private $tef = 0;
		
		/**
		 * Tiempo early de inicio
		 */
		private $tei = 0;
		
		/**
		 * Tiempo late de fin
		 */
		private $tlf = 0;
		
		/**
		 * Tiempo late de inicio
		 */
		private $tli = 0;
		
		/**
		 * Si el nodo pertenece al camino crítico
		 *
		 */
		private $critico = false;
		
		//////CONSTRUCTORES//////
		
		
		/**
		 * Constructor de la clase
		 * @param id El id del nodo. También el nombre que se mostrara.
		 * @param d la duración de la tarea que representa el nodo.
		 */
		public function Nodo($identificador, $d)
		{
			$this->id = $identificador;
			$this->duracion = $d;
		}
		
		
		//////FUNCIONES//////
		
		
		/**
		 * Añade un identificador de un nodo como precedente a este.
		 * @param identificador ID del nodo que precede
		 */
		public function addNodoPrecedente($identificador)
		{			
			 $this->nodosPrecedentes[] = $identificador;
		}
		
		/**
		 * Añade un identificador de un nodo al que este precede.
		 * @param identificador ID del nodo al que se precede
		 */
		public function addNodoPosterior($identificador)
		{			
			 $this->nodosPosteriores[] = $identificador;
		}
		
		/**
		 * Devuelve la duración de la tarea que representa este nodo
		 * @return La duración del nodo como tarea
		 */
		public function getDuracion()
		{			
			 return $this->duracion;
		}
		
		/**
		 * Devuelve la holgura total del nodo
		 * @return la holgura total del nodo
		 */
		public function getHolguraTotal()
		{			
			 return $this->tli - $this->tei;
		}
		
		/**
		 * Devuelve el ID del nodo
		 * @return El ID del nodo
		 */
		public function getID()
		{			
			 return $this->id;
		}
		
		/**
		 * Devuelve los ids de los nodos precedentes a este
		 * @return Un array con los identificadores
		 */
		public function getPrecedentes()
		{
			return $this->nodosPrecedentes;
		}
		
		/**
		 * Devuelve los ids de los nodos a los que precede este nodo
		 * @return Un array con los identificadores
		 */
		public function getPosteriores()
		{			
			return $this->nodosPosteriores;
		}
		
		/**
		 * Devuelve el Tiempo Early de Fin
		 * @return el Tiempo Early de Fin
		 */
		 public function getTEF()
		 {
			return $this->tef;
		 }
		 
		 /**
		 * Devuelve el Tiempo Early de Inicio
		 * @return el Tiempo Early de Inicio
		 */
		 public function getTEI()
		 {
			return $this->tei;
		 }
		 
		 /**
		 * Devuelve el Tiempo Late de Fin
		 * @return el Tiempo Late de Fin
		 */
		 public function getTLF()
		 {
			return $this->tlf;
		 }
		 
		 /**
		 * Devuelve el Tiempo Late de Inicio
		 * @return el Tiempo Late de Inicio
		 */
		 public function getTLI()
		 {
			return $this->tli;
		 }
		
		/**
		 * Establece el Tiempo Early de Fin
		 * @param tiempo Tiempo a establecer
		 */
		 public function setTEF($tiempo)
		 {
			 $this->tef = $tiempo;
		 }
		 
		 /**
		 * Establece el Tiempo Early de Inicio
		 * @param tiempo Tiempo a establecer
		 */
		 public function setTEI($tiempo)
		 {
			 $this->tei = $tiempo;
		 }
		 
		 /**
		 * Establece el Tiempo Late de Fin
		 * @param tiempo Tiempo a establecer
		 */
		 public function setTLF($tiempo)
		 {
			 $this->tlf = $tiempo;
		 }
		 
		 /**
		 * Establece el Tiempo late de Inicio
		 * @param tiempo Tiempo a establecer
		 */
		 public function setTLI($tiempo)
		 {
			 $this->tli = $tiempo;
		 }
		 
		 
		 /**
		 * Devuelve si el nodo está en el camino crítico.
		 * @return si está en el camino crítico o no
		 */
		 public function getCritico(){
			 return $this->critico;
		 }
		 
		 /**
		 * Convierte este nodo en parte del camino crítico
		 */
		 public function setCritico()
		 {
			 $this->critico = true;
		 }
		
	}
?>