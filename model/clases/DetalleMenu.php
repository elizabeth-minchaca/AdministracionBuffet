<?php
require_once(PATH_CLASS . 'Producto.php');
require_once(PATH_CLASS . 'Menu.php');
class DetalleMenu {
	private $id;
	private $menu;
	private $producto;
	private $cantidad;
	
	function getId() {
		return $this->id;
	}
	function getMenu() {
		return $this->menu;
	}
	function getProducto() {
		return $this->producto;
	}
	function getCantidad() {
		return $this->cantidad;
	}
	
	function setId($id) {
		$this->id = $id;
	}
	function setMenu(Menu $menu) {
		$this->menu = $menu;
	}
	function setProducto(Producto $producto) {
		$this->producto = $producto;
	}
	function setCantidad($cantidad) {
		$this->cantidad = $cantidad;
	}
	
}