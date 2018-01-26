<?php

class Configuracion {

    /**
     * @var int $id
     */
    private $id;

    /**
     * @ar string $titulo
     */
    private $titulo;

    /**
     * @var string $descripcion
     */
    private $descripcion;

    /**
     * @var string $email
     */
    private $email;

    /**
     * @var int $paginadoNumero
     */
    private $paginadoNumero;

    /**
     * @var boolean $sitioHabilitado
     */
    private $sitioHabilitado;

    /**
     * @var string $sitioHabilitadoMsj
     */
    private $sitioHabilitadoMsj;

    function getId() {
        return $this->id;
    }

    function getTitulo() {
        return $this->titulo;
    }

    function getDescripcion() {
        return $this->descripcion;
    }

    function getEmail() {
        return $this->email;
    }

    function getPaginadoNumero() {
        return $this->paginadoNumero;
    }

    function getSitioHabilitado() {
        return $this->sitioHabilitado;
    }

    function getSitioHabilitadoMsj() {
        return $this->sitioHabilitadoMsj;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setPaginadoNumero($paginadoNumero) {
        $this->paginadoNumero = $paginadoNumero;
    }

    function setSitioHabilitado($sitioHabilitado) {
        $this->sitioHabilitado = $sitioHabilitado;
    }

    function setSitioHabilitadoMsj($sitioHabilitadoMsj) {
        $this->sitioHabilitadoMsj = $sitioHabilitadoMsj;
    }

}
