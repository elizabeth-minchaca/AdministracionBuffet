<?php

class Ubicacion {

    /**
     * Id de la Entidad
     * @var integer $id
     */
    private $id;

    /**
     * Nombre de la Ubicacion
     * @var string $nombre
     */
    private $nombre;

    /**
     * Baja Lógica
     * @var boolean $bajaLogica
     */
    private $bajaLogica = 0;

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

    function setId($id) {
        $this->id = $id;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function getBajaLogica() {
        return $this->bajaLogica;
    }

    function setBajaLogica($bajaLogica) {
        $this->bajaLogica = $bajaLogica;
    }

    /**
     * *************************************************************************
     */
}
