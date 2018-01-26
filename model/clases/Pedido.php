<?php

require_once(PATH_CLASS . 'PedidoEstado.php');
require_once(PATH_CLASS . 'PedidoDetalle.php');
require_once(PATH_CLASS . 'Usuario.php');

class Pedido {

    /**
     * @var integer $id
     */
    protected $id;

    /**
     * @var PedidoEstado $estado
     */
    protected $estado;

    /**
     * @var Usuario $realizadoPor
     */
    protected $realizadoPor;

    /**
     * @var \Datetime $fecha
     */
    protected $fecha;

    /**
     * @var string $observacion
     */
    protected $observacion;

    /**
     * @var array $detalles
     */
    protected $detalles;

    /**
     * @var boolean $bajaLogica
     */
    protected $bajaLogica;

    function getId() {
        return $this->id;
    }

    function getEstado() {
        return $this->estado;
    }

    function getRealizadoPor() {
        return $this->realizadoPor;
    }

    function getFecha() {
        return $this->fecha;
    }

    function getObservacion() {
        return $this->observacion;
    }

    function getBajaLogica() {
        return $this->bajaLogica;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setEstado(PedidoEstado $estado) {
        $this->estado = $estado;
    }

    function setRealizadoPor(Usuario $realizadoPor) {
        $this->realizadoPor = $realizadoPor;
    }

    function setFecha(\Datetime $fecha) {
        $this->fecha = $fecha;
    }

    function setObservacion($observacion) {
        $this->observacion = $observacion;
    }

    function setBajaLogica($bajaLogica) {
        $this->bajaLogica = $bajaLogica;
    }

    function getDetalles() {
        return $this->detalles;
    }

    function setDetalles($detalles) {
        $this->detalles = $detalles;
    }

    function addDetalle(PedidoDetalle $detalle) {
        $this->detalles[] = $detalle;
    }

}
