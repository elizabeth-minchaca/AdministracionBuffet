<?php

require_once(PATH_CLASS . 'Rol.php');
require_once(PATH_CLASS . 'Ubicacion.php');

class Usuario {

    /**
     * Id de la Entidad
     * @var int $id
     */
    private $id;

    /**
     * Nombre de usuario
     * @var string $usuario
     */
    private $usuario;

    /**
     * Contraseña del usuario
     * @var string $clave
     */
    private $clave;

    /**
     * Nombre de la persona
     * @var string $nombre
     */
    private $nombre;

    /**
     * Apellido de la persona
     * @var string $apellido
     */
    private $apellido;

    /**
     * Documento de identidad de la persona
     * @var int $documento
     */
    private $documento;

    /**
     * Email del usuario
     * @var string $email
     */
    private $email;

    /**
     * Numero de telefono del usuario
     * @var string $telefono
     */
    private $telefono;

    /**
     * Entidad Rol
     * @var Rol $rol
     */
    private $rol;

    /**
     * Entidad Ubicacion
     * @var Ubicacion #ubicacion
     */
    private $ubicacion = null;

    /**
     * Toquen de session del usuario
     * @var string $token
     */
    private $token;

    /**
     * Identificador de Session
     * @var string $identificador
     */
    private $identificador;

    /**
     * Flag para determinar si el usuario esta habilitado o no.
     * @var boolean $habilitado
     */
    private $habilitado = 1;

    /**
     * Boolean para habilitar o deshabilitar el usuario
     * @var boolean $bajaLogica
     */
    private $bajaLogica = 0;
    
    /**
     * Token para evitar hack CSRF
     * 
     * @var string $tokeCsrf
     */
    private $tokeCsrf;

    /*
     * *************************************************************************
     * SETTERS AND GETTERS *
     * **********************
     */

    function getId() {
        return $this->id;
    }

    function getUsuario() {
        return $this->usuario;
    }

    function getClave() {
        return $this->clave;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getApellido() {
        return $this->apellido;
    }

    function getDocumento() {
        return $this->documento;
    }

    function getEmail() {
        return $this->email;
    }

    function getTelefono() {
        return $this->telefono;
    }

    function getRol() {
        return $this->rol;
    }

    function getUbicacion() {
        return $this->ubicacion;
    }

    function getToken() {
        return $this->token;
    }

    function getIdentificador() {
        return $this->identificador;
    }

    function getBajaLogica() {
        return $this->bajaLogica;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    function setClave($clave) {
        $this->clave = $clave;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setApellido($apellido) {
        $this->apellido = $apellido;
    }

    function setDocumento($documento) {
        $this->documento = $documento;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    function setRol(Rol $rol) {
        $this->rol = $rol;
    }

    function setUbicacion(Ubicacion $ubicacion) {
        $this->ubicacion = $ubicacion;
    }

    function setToken($token) {
        $this->token = $token;
    }

    function setIdentificador($identificador) {
        $this->identificador = $identificador;
    }

    function setBajaLogica($bajaLogica) {
        $this->bajaLogica = $bajaLogica;
    }

    function getHabilitado() {
        return $this->habilitado;
    }

    function setHabilitado($habilitado) {
        $this->habilitado = $habilitado;
    }
    
    function getTokeCsrf() {
        return $this->tokeCsrf;
    }

    function setTokeCsrf($tokeCsrf) {
        $this->tokeCsrf = $tokeCsrf;
    }
    
    /**
     * *************************************************************************
     */
}
