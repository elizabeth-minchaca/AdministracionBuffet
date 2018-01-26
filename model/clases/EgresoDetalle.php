<?php

require_once(PATH_CLASS . 'TipoEgreso.php');
require_once(PATH_CLASS . 'Producto.php');
require_once(PATH_CLASS . 'Compra.php');

class EgresoDetalle {

    private $id;
    private $compra;
    private $producto;
    private $cantidad;
    private $precioUnitario;
    private $egresoTipo;
    private $fecha;

    function getId() {
        return $this->id;
    }

    function getCompra() {
        return $this->compra;
    }

    function getProducto() {
        return $this->producto;
    }

    function getCantidad() {
        return $this->cantidad;
    }

    function getPrecioUnitario() {
        return $this->precioUnitario;
    }

    function getEgresoTipo() {
        return $this->egresoTipo;
    }

    function getFecha() {
        return $this->fecha;
    }

    function getFechaFormateada() {
        $fechaDate = new DateTime($this->fecha);
        return date_format($fechaDate, 'd/m/Y H:i:s');
    }

    function setId($id) {
        $this->id = $id;
    }

    function setCompra(Compra $compra) {
        $this->compra = $compra;
    }

    function setProducto(Producto $producto) {
        $this->producto = $producto;
    }

    function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
    }

    function setPrecioUnitario($cantidad) {
        $this->precioUnitario = $cantidad;
    }

    function setEgresoTipo(TipoEgreso $cantidad) {
        $this->egresoTipo = $cantidad;
    }

    function setFecha($fecha) {
        $this->fecha = $fecha;
    }

}
