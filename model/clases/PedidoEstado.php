<?php

class PedidoEstado {

    /**
     * @var integer $id
     */
    protected $id;

    /**
     * @var string $nombre
     */
    protected $nombre;

    /**
     * @var boolean $bajaLogica
     */
    protected $bajaLogica;

    function getId() {
        return $this->id;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getBajaLogica() {
        return $this->bajaLogica;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setBajaLogica($bajaLogica) {
        $this->bajaLogica = $bajaLogica;
    }

}
