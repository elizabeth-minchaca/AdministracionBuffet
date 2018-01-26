<?php

require_once(PATH_VIEW . 'DefaultView.php');
require_once(PATH_VIEW . 'ErrorHandlerView.php');
require_once(PATH_MODEL . 'ConfiguracionModel.php');

class ErrorHandlerController {

    private static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Pagina de error
     */
    public function notFoundAction($parameters = array()) {
        $view = new ErrorHandlerView();
        $parameters['user'] = SessionController::getInstance()->getUsuarioAction();
        $parameters['config'] = ConfiguracionModel::getInstance()->getConfiguracion();
        header("HTTP/1.0 404 Not Found");
        return $view->renderNotFound($parameters);
    }

    /**
     * Pagina de acceso denegado
     */
    public function accessDeniedPageAction($parameters = array()) {
        $view = new DefaultView();
        $parameters['config'] = ConfiguracionModel::getInstance()->getConfiguracion();
        $parameters['error_msj'] = 'Acceso denegado.';
        $parameters['user'] = SessionController::getInstance()->getUsuarioAction();
        header("HTTP/1.0 401 Unauthorized");
        return $view->renderLogin($parameters);
    }

    /**
     * Pagina deshabilitada
     */
    public function disabledPageAction($parameters = array()) {
        $view = new ErrorHandlerView();
        $parameters['config'] = ConfiguracionModel::getInstance()->getConfiguracion();
        $parameters['user'] = SessionController::getInstance()->getUsuarioAction();
        header("HTTP/1.0 503 Service Unavailable");
        return $view->renderDisabledPage($parameters);
    }
    
    /**
     * Pagina de acceso denegado
     */
    public function csrfError($parameters = array()) {
         $view = new DefaultView();
        $parameters['config'] = ConfiguracionModel::getInstance()->getConfiguracion();
        $parameters['error_msj'] = 'Ya caducó el formulario.';
        $parameters['user'] = SessionController::getInstance()->getUsuarioAction();
        header("HTTP/1.0 401 Unauthorized");
        return $view->renderLogin($parameters);
    }

}
