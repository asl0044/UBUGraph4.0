<?php
	/**
	 * Clase que implementa una actividad (arista) en un grafo para una solucion PERT
	 * @author Ruben Arranz Alonso de L.
	 */	
	class Actividad
	{
		//////ATRIBUTOS//////
		
		/**
		 * Los IDs de las actividades que preceden a la nuestra
		 */
		private $actividadesPrecedentes = array();
		
		/**
		 * Los IDs de las actividades que las que esta es precedente
		 */
		private $actividadesPosteriores = array();
		
		/**
		 * La duracion de la tarea que representa la actividad.
		 */
		private $duracion;
		
		/**
		 * Si la actividad es ficticia o no
		 */
		private $ficticia = false;
		
		/**
		 * El ID de la actividad, también es el nombre que se mostrara.
		 */
		private $id;
		
		/**
		 * ID del nod en el que se inicia la actividad
		 */
		private $nodoDestino = -1;
		
		/**
		 * ID del nodo en el que finaliza la actividad
		 */
		private $nodoOrigen = -1;
		
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
		
		//////CONSTRUCTORES//////
		
		
		/**
		 * Constructor de la clase
		 * @param id El id de la actividad. También el nombre que se mostrara.
		 * @param d la duración de la tarea que representa la actividad.
		 */
		public function Actividad($identificador, $d)
		{
			$this->id = $identificador;
			$this->duracion = $d;
		}
		
		
		//////FUNCIONES//////
		
		
		/**
		 * Añade un identificador de una actividad como precedente a esta.
		 * @param identificador ID de la actividad que precede
		 */
		public function addActividadPrecedente($identificador)
		{			
			 $this->actividadesPrecedentes[] = $identificador;
		}
		
		/**
		 * Añade un identificador de una actividad al que esta precede.
		 * @param identificador ID de la actividad a la que se precede
		 */
		public function addActividadPosterior($identificador)
		{			
			 $this->actividadesPosteriores[] = $identificador;
		}
		
		/**
		 * Elimina todas las actividades precedentes
		 */
		public function clearActividadesPrecedentes()
		{
			 $this->actividadesPrecedentes = array();
		}
		
		/**
		 *  Elimina todas las actividades posteriores
		 */
		public function clearActividadesPosteriores()
		{	
			$this->actividadesPosteriores = array();
		}
		
		/**
		 * Elimina un identificador de una actividad como precedente a esta.
		 * @param identificador ID de la actividad
		 */
		public function eliminarActividadPrecedente($identificador)
		{
			 unset($this->actividadesPrecedentes[$identificador]);
		}
		
		/**
		 * Elimina un identificador de una actividad como que esta precede.
		 * @param identificador ID de la actividad
		 */
		public function eliminarActividadPosterior($identificador)
		{	
			unset($this->actividadesPosteriores[$identificador]);
		}
		
		/**
		 * Establece el ID del nodo en el que finaliza la actividad
		 * @param id El identificador del Nodo
		 */
		public function establecerNodoDestino($id)
		{
			$this->nodoDestino = $id;;
		}
		
		/**
		 * Establece el ID del nodo en el que se origina la actividad
		 * @param id El identificador del Nodo
		 */
		public function establecerNodoOrigen($id)
		{
			$this->nodoOrigen = $id;
		}
		
		/**
		 * Devuelve la duración de la tarea que representa esta actividad
		 * @return La duración de la actividad como tarea
		 */
		public function getDuracion()
		{			
			 return $this->duracion;
		}
		
		/**
		 * Devuelve si esta actividad es ficticia o no.
		 * @return si es o no ficticia
		 */
		public function getFicticia()
		{
			return $this->ficticia;
		}
		
		/**
		 * Devuelve la holgura total de la actividad
		 * @return la holgura total de la actividad
		 */
		public function getHolguraTotal()
		{			
			 return $this->tli - $this->tei;
		}
		
		/**
		 * Devuelve el ID de la actividad
		 * @return El ID de la actividad
		 */
		public function getID()
		{			
			 return $this->id;
		}
		
		/**
		 * Obtiene el ID del nodo en el que finaliza la actividad
		 * @return Un identificador
		 */
		public function getNodoDestino()
		{
			return $this->nodoDestino;
		}
		
		/**
		 * Obtiene el ID del nodo en el que se origina la actividad
		 * @return Un identificador
		 */
		public function getNodoOrigen()
		{
			return $this->nodoOrigen;
		}
		
		/**
		 * Devuelve los ids de las actividad precedentes a esta
		 * @return Un array con los identificadores
		 */
		public function getPrecedentes()
		{
			return $this->actividadesPrecedentes;
		}
		
		/**
		 * Devuelve los ids de las actividad a los que precede esta actividad
		 * @return Un array con los identificadores
		 */
		public function getPosteriores()
		{			
			return $this->actividadesPosteriores;
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
		 * Convierte esta actividad en ficticia
		 */
		public function setFicticia()
		{
			$this->ficticia = true;
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
	}
?>