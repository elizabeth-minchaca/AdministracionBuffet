<?php
class Proveedor {
	private $id;
	private $nombre;
	private $cuit;

	function getId() {
		return $this->id;
	}

	function getNombre() {
		return $this->nombre;
	}

	function getCuit() {
		return $this->cuit;
	}

	function setId($id) {
		$this->id = $id;
	}

	function setNombre($nombre) {
		$this->nombre = $nombre;
	}

	function setCuit($cuit) {
		$this->cuit = $cuit;
	}

}