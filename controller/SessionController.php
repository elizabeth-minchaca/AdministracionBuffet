<?php

require_once(PATH_VIEW . 'ErrorHandlerView.php');
require_once(PATH_MODEL . 'UsuarioModelOrm.php');
require_once(PATH_CLASS . 'Usuario.php');
require_once(PATH_CONTROLLER . 'ErrorHandlerController.php');

class SessionController {

    private static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 
     */
    private function getId() {
        return (isset($_SESSION['ID'])) ? $_SESSION['ID'] : NULL;
    }

    /**
     * 
     */
    private function setId($id) {
        $_SESSION['ID'] = $id;
    }

    /**
     * 
     */
    private function setTokenCSRF($str) {
        $_SESSION['TOKEN_CSRF'] = $str;
    }

    /**
     * 
     */
    public function getTokenCSRF() {
        return (isset($_SESSION['TOKEN_CSRF'])) ? $_SESSION['TOKEN_CSRF'] : NULL;
    }

    /**
     * 
     */
    private function getRol() {
        return (isset($_SESSION['ROL'])) ? $_SESSION['ROL'] : NULL;
    }

    /**
     * 
     */
    private function setRol($rol) {
        $_SESSION['ROL'] = $rol;
    }

    /**
     * 
     */
    private function getCookieId() {
        return (isset($_COOKIE["BUFFET_ID"])) ? $_COOKIE["BUFFET_ID"] : NULL;
    }

    /**
     * 
     */
    private function setCookieId($id) {
        setcookie("BUFFET_ID", $id, time() + 60 * 60 * 24 * 30);
    }

    /**
     * 
     */
    private function getCookieToken() {
        return (isset($_COOKIE["BUFFET_TOKEN"])) ? $_COOKIE["BUFFET_TOKEN"] : NULL;
    }

    /**
     * 
     */
    private function setCookieToken($token) {
        setcookie("BUFFET_TOKEN", $token, time() + 60 * 60 * 24 * 30);
    }

    /**
     * Generador de claves token aleatorias
     */
    private function generateRandomToken() {
        return $this->sessionHash((string) rand(1, 99999));
    }

    /**
     * *************************************************************************
     * 
     * Funciones públicas del manejo de Sessión
     * *****************************************
     */

    /**
     * Funcion de Hash de Session
     */
    public function sessionHash($str) {
        return hash('sha512', $str);
    }

    /**
     * Función de login del sismtema
     * 
     * @var string $user
     * @var string $password
     * @var boolean $rememberme
     * @return boolean Si se inició o no la session en el sistema
     */
    public function loginAction($user, $password, $rememberme = FALSE) {
        $model = UsuarioModelOrm::getInstance();
        $usuario = $model->getUsuario($user, $this->sessionHash($password)); //encripta la contraseña para su cotejamiento en la BD
        if (!$usuario) {
            return FALSE;
        }//Se encontro el usuario registrado en el sistema
        @session_start();
        $usuario->setIdentificador($this->sessionHash($usuario->getUsuario()));
        if ($rememberme) {
            $newToken = $this->generateRandomToken();
            $this->setCookieId($usuario->getIdentificador());
            $this->setCookieToken($newToken);
            $usuario->setToken($newToken);
        }
        $this->setTokenCSRF($this->generateRandomToken());
        $model->updateEntity($usuario); //Se persiste en la BD
        $this->setId($usuario->getId());
        $this->setRol($usuario->getRol()->getNombre());
        return TRUE;
    }

    /**
     * Función Loguot del Sistema
     * 
     * @return Boolean Retorna TRUE si se cerró la session correctamente o FALSE en caso contrario.
     */
    public function logoutAction() {
        @session_start();
        if (!$this->isLoginAction()) {
            return TRUE;
        }
        @session_destroy();
        $model = UsuarioModelOrm::getInstance();
        $usuario = $model->getById($this->getId());
        $usuario->setToken('NO_TOKEN');
        return ($model->updateEntity($usuario)) ? TRUE : FALSE;
    }

    /**
     * Función HasRol
     * 
     * @param string $rol Nombre del ROL que hay aue verificar
     * @return Boolean Retorna TRUE si el rol como parámetro es el que tiene el usuario logueado en el sistema. Retorna FALSE en caso contrario.
     */
    public function hasRolAction($rol) {
        @session_start();
        return ($this->getRol() == $rol) ? TRUE : FALSE;
    }

    /**
     * Retorna el usuario logueado en el Sistema o null en caso contrario
     * 
     * @return NULL Si no encuentraal usuario logueado en la BD
     * @return Usuario Entidad Usuario
     */
    public function getUsuarioAction() {

        if (!$this->isLoginAction()) {
            return NULL;
        }
        $user = UsuarioModelOrm::getInstance()->getById($this->getId());
        $user->setTokeCsrf($this->getTokenCSRF());
        return $user;
    }

    /**
     * Retorna un boolean describiendo si esta o no iniciada una session
     * 
     * @return boolean 
     */
    public function isLoginAction() {
        @session_start();
        if ($this->getId()) {
            return TRUE;
        }
        if (!($this->getCookieId() || $this->getCookieToken())) {
            return FALSE;
        }
        //No hay session pero si hay cookies de sessiones guardadas mediante rememberme
        $model = UsuarioModelOrm::getInstance();
        $usuario = $model->getBySession($this->getCookieId(), $this->getCookieToken());
        if (!$usuario) {
            return FALSE;
        }
        $this->setTokenCSRF($this->generateRandomToken());
        $usuario->setIdentificador($this->sessionHash($usuario->getUsuario()));
        $usuario->setToken($this->generateRandomToken());
        $this->setCookieId($usuario->getIdentificador());
        $this->setCookieToken($usuario->getToken());
        $this->setId($usuario->getId());
        $this->setRol($usuario->getRol()->getNombre());
        return ($model->updateEntity($usuario)) ? TRUE : FALSE; //Se persiste en la BD
    }

}
