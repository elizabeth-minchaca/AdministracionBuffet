<?php

require_once(PATH_CLASS . 'Producto.php');
require_once(PATH_CLASS . 'Venta.php');
require_once(PATH_CLASS . 'TipoIngreso.php');

class IngresoDetalle {

    /**
     * Id
     * 
     * @var int $id
     */
    protected $id;

    /**
     * Cantidad de productos del mismo tipo
     * 
     * @var int $cantidad
     */
    protected $cantidad = 1;

    /**
     * Venta
     * 
     * @var Venta $venta
     */
    protected $venta;

    /**
     * Precio de venta unitario por producto
     * 
     * @var float $precio_unitario
     */
    protected $precio_unitario;

    /**
     * Descripción del ingreso
     * 
     * @var string $descripcion
     */
    protected $descripcion;

    /**
     * Fecha y hora de la operación
     * 
     * @var \Datetime $fecha
     */
    protected $fecha;

    /**
     * Prodcuto
     * 
     * @var Producto $producto
     */
    protected $producto;

    /**
     * Tipo de Ingreso
     * 
     * @var TipoIngreso $tipo
     */
    protected $tipo;

    /**
     * Baja Logica
     * 
     * @var boolean $bajaLogica
     */
    protected $bajaLogica = 0;

    /*
     * *************************************************************************
     * SETTERS AND GETTERS *
     * **********************
     */

    function getId() {
        return $this->id;
    }

    function getCantidad() {
        return $this->cantidad;
    }

    function getPrecio_unitario() {
        return $this->precio_unitario;
    }

    function getDescripcion() {
        return $this->descripcion;
    }

    function getFecha() {
        return $this->fecha;
    }

    function getProducto() {
        return $this->producto;
    }

    function getTipo() {
        return $this->tipo;
    }

    function getBajaLogica() {
        return $this->bajaLogica;
    }

    function getVenta() {
        return $this->venta;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
    }

    function setPrecio_unitario($precio_unitario) {
        $this->precio_unitario = $precio_unitario;
    }

    function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    function setFecha(\Datetime $fecha) {
        $this->fecha = $fecha;
    }

    function setProducto(Producto $producto) {
        $this->producto = $producto;
    }

    function setTipo(TipoIngreso $tipo) {
        $this->tipo = $tipo;
    }

    function setBajaLogica($bajaLogica) {
        $this->bajaLogica = $bajaLogica;
    }

    function setVenta(Venta $venta) {
        $this->venta = $venta;
    }

    /**
     * *************************************************************************
     */
}
