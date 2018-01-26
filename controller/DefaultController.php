<?php

require_once(PATH_VIEW . 'DefaultView.php');
require_once(PATH_CONTROLLER . 'SessionController.php');
require_once(PATH_CONTROLLER . 'Controller.php');
require_once(PATH_CONTROLLER . 'UsuarioController.php');
require_once(PATH_CONTROLLER . 'PruebasBotController.php');
require_once(PATH_MODEL . 'ConfiguracionModel.php');
require_once(PATH_MODEL . 'UsuarioModelOrm.php');
require_once(PATH_CONTROLLER . 'ErrorHandlerController.php');

class DefaultController extends Controller {

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
    public function registerAction() {
        $view = new DefaultView();
        return $view->renderRegister(array(
                    'usuario_ubicacion' => UbicacionModelOrm::getInstance()->getAll(),
                    'user' => SessionController::getInstance()->getUsuarioAction(),
                    "config" => ConfiguracionModel::getInstance()->getConfiguracion())
        );
    }

    /**
     * 
     */
    public function indexAction() {
        $view = new DefaultView();
        return $view->renderIndex(array(
                    'user' => SessionController::getInstance()->getUsuarioAction(),
                    'config' => ConfiguracionModel::getInstance()->getConfiguracion(),
                    'arreglo' => PruebasBotController::getInstance()->menuDelDia()
        ));
    }

    /**
     * Home
     * 
     */
    public function homeAction() {
        $view = new DefaultView();
        $this->validateAccess();
        return $view->renderHome(array(
                    'user' => SessionController::getInstance()->getUsuarioAction(),
                    "config" => ConfiguracionModel::getInstance()->getConfiguracion()
        ));
    }

    /**
     * Login
     * 
     */
    public function loginAction() {
        $view = new DefaultView();
        SessionController::getInstance()->logoutAction();
        return $view->renderLogin(array(
                    "config" => ConfiguracionModel::getInstance()->getConfiguracion()
        ));
    }

    /**
     * 
     */
    public function accessAction() {
        $view = new DefaultView();
        if (!SessionController::getInstance()->loginAction($_POST['usuario'], $_POST['passwd'], (isset($_POST['recordarme']) && $_POST['recordarme'] == 'si') ? TRUE : FALSE)) {
            return $view->renderLogin(array(
                        "config" => ConfiguracionModel::getInstance()->getConfiguracion(),
                        'error_msj' => 'Usuario y/o contraseña incorrecto.'
            ));
        }
        header('Location: ' . ROOT_URL . 'index.php?action=home');
    }

    /**
     * 
     */
    public function registerProcessAction() {
        $view = new DefaultView();
        $result = UsuarioController::getInstance()->newUserOnline();
        if ($result['valido']) {
            return $view->renderIndex(array(
                        'success_msj' => 'Fue registrado correctamente en el Sistema. Se le enviará un email cuando su cuenta este lista para usar.',
                        'user' => SessionController::getInstance()->getUsuarioAction(),
                        "config" => ConfiguracionModel::getInstance()->getConfiguracion())
            );
        } else {
            return $view->renderRegister(array(
                        'error_msj' => $result['error_msj'],
                        'usuario_ubicacion' => UbicacionModelOrm::getInstance()->getAll(),
                        'usuario' => $result['usuario'],
                        'user' => SessionController::getInstance()->getUsuarioAction(),
                        "config" => ConfiguracionModel::getInstance()->getConfiguracion())
            );
        }
    }

    /**
     * 
     * 
     */
    public function logoutAction() {
        return $this->loginAction();
    }

}
