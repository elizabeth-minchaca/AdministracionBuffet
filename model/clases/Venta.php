<?php

require_once(PATH_CLASS . 'Pedido.php');
require_once(PATH_CLASS . 'Usuario.php');
require_once(PATH_CLASS . 'IngresoDetalle.php');

class Venta {

    /**
     * @var integer $id
     */
    protected $id;

    /**
     * @var Pedido $pedido
     */
    protected $pedido;

    /**
     * @var Usuario $empleado
     */
    protected $empleado;

    /**
     * @var \Datetime $fecha
     */
    protected $fecha;

    /**
     * @var boolean $bajaLogica
     */
    protected $bajaLogica = 0;

    /**
     * @var array $detalles
     */
    protected $detalles;

    public function __construct() {
        $this->detalles = array();
    }

    function getId() {
        return $this->id;
    }

    function getPedido() {
        return $this->pedido;
    }

    function getEmpleado() {
        return $this->empleado;
    }

    function getFecha() {
        return $this->fecha;
    }

    function getBajaLogica() {
        return $this->bajaLogica;
    }

    function getDetalles() {
        return $this->detalles;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setPedido(Pedido $pedido) {
        $this->pedido = $pedido;
    }

    function setEmpleado(Usuario $empleado) {
        $this->empleado = $empleado;
    }

    function setFecha(\Datetime $fecha) {
        $this->fecha = $fecha;
    }

    function setBajaLogica($bajaLogica) {
        $this->bajaLogica = $bajaLogica;
    }

    function setDetalles($detalles) {
        $this->detalles = $detalles;
    }

    public function addDetalle(IngresoDetalle $detalle) {
        $vec = $this->getDetalles();
        $vec[] = $detalle;
    }

}
