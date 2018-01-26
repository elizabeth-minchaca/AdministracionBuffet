<?php

require_once (PATH_CLASS . 'Proveedor.php');

class Compra {

    private $id;
    private $proveedor;
    private $fecha;
    private $finalizada;
    private $factura;
    private $egresosDetalles = array();

    function getId() {
        return $this->id;
    }

    function getProveedor() {
        return $this->proveedor;
    }

    function getFecha() {
        return $this->fecha;
    }

    function getFinalizada() {
        return $this->finalizada;
    }

    function getEstadoCompra() {
        return ($this->finalizada) ? "Finalizada" : "Pendiente";
    }

    function getFactura() {
        return $this->factura;
    }

    function getEgresosDetalles() {
        return $this->egresosDetalles;
    }

    function getFechaFormateada() {
        $fechaDate = new DateTime($this->fecha);
        return date_format($fechaDate, 'd/m/Y');
    }

    function setId($id) {
        $this->id = $id;
    }

    function setProveedor(Proveedor $proveedor) {
        $this->proveedor = $proveedor;
    }

    function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    function setFinalizada($finalizada) {
        $this->finalizada = $finalizada;
    }

    function setFactura($factura) {
        $this->factura = $factura;
    }

    function setEgresosDetalles($lista) {
        $this->egresosDetalles = $lista;
    }

}
