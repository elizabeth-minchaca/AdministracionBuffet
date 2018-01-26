<?php
class TipoEgreso{
	private $id;
	private $nombre;
	private $bajaLogica;
	
	function getId(){
		return $this->id;
	}
	
	function getNombre(){
		return $this->nombre;
	}
	
	function getBajaLogica(){
		return $this->bajaLogica;
	}
	
	
	/**Setters**/
	function setId($id){
		$this->id = $id;
	}
	function setNombre($nombre){
		$this->nombre = $nombre;
	}
	function setBajaLogica($bajaLogica){
		$this->bajaLogica = $bajaLogica;
	}
}