<?php

class TipoIngreso {

    /**
     * Id
     * 
     * @var int $id
     */
    protected $id;

    /**
     * Nombre del Tipo
     * 
     * @var string $nombre
     */
    protected $nombre;

    /**
     * Baja Lógica
     * 
     * @var boolean $bajaLogica
     */
    protected $bajaLogica;

    /*
     * *************************************************************************
     * SETTERS AND GETTERS *
     * **********************
     */

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

    /**
     * *************************************************************************
     */
}
