<?php

require_once(PATH_CLASS . 'DetalleMenu.php');

class Menu {

    private $id;
    private $fecha;
    private $habilitada;
    private $menuDetalles = array();

    function getId() {
        return $this->id;
    }

    function getFecha() {
        return $this->fecha;
    }

    function getFechaFormateada() {
        $fechaDate = new DateTime($this->fecha);
        return date_format($fechaDate, 'd/m/Y');
    }

    function getHabilitada() {
        return $this->habilitada;
    }

    function getEstadoMenu() {
        return ($this->habilitada) ? "Habilitado" : "Deshabilitado";
    }

    function getPrecioMenu() {
        $res = 0;
        foreach ($this->menuDetalles as $detalle) {
            $res = $res + ($detalle->getCantidad() * $detalle->getProducto()->getPrecioVentaUnitario());
        }
        return $res;
    }

    function isProducto($idProducto) {
        $detalles = $this->getMenuDetalles();
        $isTrue = false;
        foreach ($detalles as $detalle) {
            if ($detalle->getProducto()->getId() === $idProducto) {
                $isTrue = true;
            }
        }
        return $isTrue;
    }

    function getMenuDetalles() {
        return $this->menuDetalles;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    function setHabilitada($habilitada) {
        $this->habilitada = $habilitada;
    }

    function setMenuDetalles($lista) {
        $this->menuDetalles = $lista;
    }

}
