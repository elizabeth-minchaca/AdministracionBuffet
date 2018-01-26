<?php

/**
 * 
 */
class Rol {

    /**
     * Id de la Entidad
     * @var integer $id
     */
    private $id;

    /**
     * Nombre del Rol
     * @var string $nombre
     */
    private $nombre;

    /**
     * Baja lógica de la Entidad
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
