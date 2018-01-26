<?php

/*
  ini_set('display_startup_errors', 1);
  ini_set('display_errors', 1);
  error_reporting(-1);
 */

require_once (PATH_VIEW . 'ConfiguracionView.php');
require_once (PATH_VIEW . 'ErrorHandlerView.php');
require_once (PATH_VIEW . 'ErrorPageView.php');
require_once (PATH_CLASS . 'Configuracion.php');
require_once (PATH_MODEL . 'ConfiguracionModel.php');
require_once (PATH_CONTROLLER . 'SessionController.php');
require_once (PATH_CONTROLLER . 'Controller.php');

class ConfiguracionController extends Controller {

    private static $instance;

    /**
     */
    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self ();
        }
        return self::$instance;
    }

    public function editarConfiguracion() {
        $this->validateAccess(array('ADMINISTRADOR'));
        $config = ConfiguracionModel::getInstance()->getConfiguracion();
        if (!$config) {
            return ErrorHandlerController::getInstance()->notFoundAction();
        }
        $view = new ConfiguracionView ();
        return $view->renderFormConfiguracion(array(
                    "config" => $config,
                    'user' => SessionController::getInstance()->getUsuarioAction(),
        ));
    }

    public function mostrarConfiguracion() {
        $this->validateAccess(array('ADMINISTRADOR'));
        $config = ConfiguracionModel::getInstance()->getConfiguracion();
        if (!$config) {
            return ErrorHandlerController::getInstance()->notFoundAction();
        }
        $view = new ConfiguracionView ();
        $view->renderMostrarConfiguracion(array(
            "config" => $config,
            'user' => SessionController::getInstance()->getUsuarioAction()
        ));
    }

    public function editSubmitAction() {
        $this->validateAccess(array('ADMINISTRADOR'));
        
        if (!$this->validateCsrf()) {//Validacion CSRF!!!***
        	return NULL;
        }//*************************************************
        
        //Validacion XSS!!********
        $_POST ['titulo'] = strip_tags($_POST ['titulo']);
        $_POST ['email'] = strip_tags($_POST ['email']);
        $_POST ['descripcion'] = strip_tags($_POST ['descripcion']);
        $_POST ['sitioHabilitadoMsj'] = strip_tags($_POST ['sitioHabilitadoMsj']);
        //************************
        
        $config = ConfiguracionModel::getInstance()->getConfiguracion();
        $view = new ConfiguracionView();
        $validacion = $this->validate($_POST);
        if (!$validacion['valido']) {
            return $view->renderFormConfiguracion(array(
                        "config" => $config,
                        'error_msj' => $validacion['error_msj'],
                        'user' => SessionController::getInstance()->getUsuarioAction()
            ));
        }
        if (!$config) {
            return ErrorHandlerController::getInstance()->notFoundAction();
        }
        $config->setDescripcion($_POST['descripcion']);
        $config->setEmail($_POST['email']);
        $config->setPaginadoNumero((int) $_POST['paginadoNumero']);
        $config->setSitioHabilitado(filter_var($_POST['sitioHabilitado'], FILTER_VALIDATE_BOOLEAN, array('flags' => FILTER_NULL_ON_FAILURE))); //$this->isBoolean($_POST['sitioHabilitado']));
        $config->setSitioHabilitadoMsj($_POST['sitioHabilitadoMsj']);
        $config->setTitulo($_POST['titulo']);
        $result = ConfiguracionModel::getInstance()->actualizar($config);
        if ($result) {
            $view->renderMostrarConfiguracion(array(
                "config" => $config,
                'success_msj' => 'Los datos de configuracion han sido modificados',
                'user' => SessionController::getInstance()->getUsuarioAction()
            ));
        } else {
            $view->renderMostrarConfiguracion(array(
                "config" => ConfiguracionModel::getInstance()->getConfiguracion(),
                "error_msj" => 'No se pudo actualizar los datos de configuracion',
                'user' => SessionController::getInstance()->getUsuarioAction()
            ));
        }
    }

    private function validate($arreglo) {
        $result['valido'] = FALSE;
        if (!isset($arreglo['titulo']) || $this->isEmpty(trim($arreglo['titulo']))) {
            $result['error_msj'] = 'El titulo no puede estar en blanco.';
            return $result;
        }
        if (!isset($arreglo['descripcion']) || $this->isEmpty(trim($arreglo['descripcion']))) {
            $result['error_msj'] = 'La descripcion no puede estar en blanco.';
            return $result;
        }
        if (!isset($arreglo['email']) || !$this->isEmail(trim($arreglo['email']))) {
            $result['error_msj'] = 'El email no es válido o está en blanco.';
            return $result;
        }
        if (!$this->isBoolean($arreglo['sitioHabilitado'])) {
            $result['error_msj'] = 'Seleccione un estado de habilitacion.';
            return $result;
        }
        if (!isset($arreglo['sitioHabilitadoMsj']) || $this->isEmpty(trim($arreglo['sitioHabilitadoMsj']))) {
            $result['error_msj'] = 'El mensaje de deshabilitacion no puede estar en blanco.';
            return $result;
        }
        $result['valido'] = TRUE;
        return $result;
    }

}
