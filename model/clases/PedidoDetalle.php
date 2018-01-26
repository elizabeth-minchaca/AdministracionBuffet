<?php

require_once(PATH_CLASS . 'Producto.php');

class PedidoDetalle {

    /**
     * @var integer $id
     */
    protected $id;

    /**
     * @var integer $id_pedido
     */
    protected $id_pedido;

    /**
     * @var Producto $producto
     */
    protected $producto;

    /**
     * @var integer $cantidad
     */
    protected $cantidad;

    /**
     * @var boolean $bajaLogica
     */
    protected $bajaLogica;

    function getId() {
        return $this->id;
    }

    function getId_pedido() {
        return $this->id_pedido;
    }

    function getProducto() {
        return $this->producto;
    }

    function getCantidad() {
        return $this->cantidad;
    }

    function getBajaLogica() {
        return $this->bajaLogica;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setId_pedido($id_pedido) {
        $this->id_pedido = $id_pedido;
    }

    function setProducto(Producto $producto) {
        $this->producto = $producto;
    }

    function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
    }

    function setBajaLogica($bajaLogica) {
        $this->bajaLogica = $bajaLogica;
    }

}
