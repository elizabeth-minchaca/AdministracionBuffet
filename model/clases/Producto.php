<?php

require_once(PATH_CLASS . 'Categoria.php');

class Producto {

    private $id;
    private $nombre;
    private $marca;
    private $codigoBarra;
    private $stock;
    private $stockMinimo;
    private $categoria;
    private $precioVentaUnitario;
    private $descripcion;
    private $fechaAlta;
    private $bajaLogica;

    function getId() {
        return $this->id;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getMarca() {
        return $this->marca;
    }

    function getCodigoBarra() {
        return $this->codigoBarra;
    }

    function getStock() {
        return $this->stock;
    }

    function getStockMinimo() {
        return $this->stockMinimo;
    }

    function getCategoria() {
        return $this->categoria;
    }

    function getPrecioVentaUnitario() {
        return $this->precioVentaUnitario;
    }

    function getDescripcion() {
        return $this->descripcion;
    }

    function getFechaAlta() {
        return $this->fechaAlta;
    }

    function getBajaLogica() {
        return $this->bajaLogica;
    }

    /** Setters * */
    function setId($id) {
        $this->id = $id;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setMarca($marca) {
        $this->marca = $marca;
    }

    function setCodigoBarra($codigoBarra) {
        $this->codigoBarra = $codigoBarra;
    }

    function setStock($stock) {
        $this->stock = $stock;
    }

    function setStockMinimo($stockMinimo) {
        $this->stockMinimo = $stockMinimo;
    }

    function setCategoria(Categoria $categoria) {
        $this->categoria = $categoria;
    }

    function setPrecioVentaUnitario($precio) {
        $this->precioVentaUnitario = $precio;
    }

    function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    function setFechaAlta($fecha) {
        $this->fechaAlta = $fecha;
    }

    function setBajaLogica($bajaLogica) {
        $this->bajaLogica = $bajaLogica;
    }

}
