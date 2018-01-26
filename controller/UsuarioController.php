<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

require_once(PATH_VIEW . 'UsuarioView.php');
require_once(PATH_MODEL . 'UsuarioModelOrm.php');
require_once(PATH_MODEL . 'RolModelOrm.php');
require_once(PATH_MODEL . 'UbicacionModelOrm.php');
require_once(PATH_MODEL . 'ConfiguracionModel.php');
require_once(PATH_CLASS . 'Usuario.php');
require_once(PATH_CONTROLLER . 'ErrorHandlerController.php');
require_once(PATH_CONTROLLER . 'SessionController.php');
require_once(PATH_CONTROLLER . 'Controller.php');

class UsuarioController extends Controller {

    private static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function validate($arreglo, $tipo = 'new') {
        $result['valido'] = FALSE;
        if (!isset($arreglo['nombre']) || $this->isEmpty($arreglo['nombre'])) {
            $result['error_msj'] = 'El nombre no puede estar en blanco.';
            return $result;
        }
        if (!isset($arreglo['apellido']) || $this->isEmpty($arreglo['apellido'])) {
            $result['error_msj'] = 'EL apellido no puede estar en blanco.';
            return $result;
        }
        if (!isset($arreglo['dni']) || !$this->isInteger($arreglo['dni'])) {
            $result['error_msj'] = 'El DNI no es un número válido o está en blanco.';
            return $result;
        }
        if (!isset($arreglo['email']) || !$this->isEmail($arreglo['email'])) {
            $result['error_msj'] = 'El email no es válido o está en blanco.';
            return $result;
        }
        if (!isset($arreglo['telefono']) || $this->isEmpty($arreglo['telefono'])) {
            $result['error_msj'] = 'El teléfono no puede estar en blanco.';
            return $result;
        }
        if ($tipo != 'online') {
            if (!isset($arreglo['rol']) || $this->isEmpty($arreglo['rol'])) {
                $result['error_msj'] = 'No se ha seleccionado un rol válido.';
                return $result;
            }
            $rol = RolModelOrm::getInstance()->getByNombre('USUARIO ONLINE');
            if ($arreglo['rol'] == $rol->getId() && (!isset($arreglo['ubicacion']) || $this->isEmpty($arreglo['ubicacion']))) {
                $result['error_msj'] = 'La ubicación debe estar seleccionada cuando el usuario es del tipo -USUARIO ONLINE-.';
                return $result;
            }
        }
        if ($tipo == 'new' || $tipo == 'online') {
            if (!isset($arreglo['usuario']) || $this->isEmpty($arreglo['usuario']) || UsuarioModelOrm::getInstance()->existUsuario($arreglo['usuario'])) {
                $result['error_msj'] = 'El nombre de usuario ya se encuentra registrado en el sistema o esta en blanco.';
                return $result;
            }
            if (!isset($arreglo['password']) || $this->isEmpty($arreglo['password']) || !$this->isPasswordSecured($arreglo['password'])) {
                $result['error_msj'] = 'La contraseña esta en blanco o no cumple con los requisitos de seguridad.';
                return $result;
            }
            if (!isset($arreglo['passwordConfirm']) || $this->isEmpty($arreglo['passwordConfirm']) || $arreglo['passwordConfirm'] != $arreglo['password']) {
                $result['error_msj'] = 'Las contraseñas ingresadas no coinciden.';
                return $result;
            }
        }
        if ($tipo == 'edit') {
            if (!isset($arreglo['usuario']) || $this->isEmpty($arreglo['usuario'])) {
                $result['error_msj'] = 'El nombre de usuario no puede estar en blanco.';
                return $result;
            }
            $user = UsuarioModelOrm::getInstance()->getById($arreglo['id']);
            if ($user->getUsuario() !== $arreglo['usuario'] && UsuarioModelOrm::getInstance()->existUsuario($arreglo['usuario'])) {
                $result['error_msj'] = 'El nombre de usuario ya se encuentra registrado en el sistema.';
                return $result;
            }
            if (isset($arreglo['password']) && !$this->isEmpty($arreglo['password']) && !$this->isPasswordSecured($arreglo['password'])) {
                $result['error_msj'] = 'La contraseña no cumple con los requisitos de seguridad.';
                return $result;
            }
            if (isset($arreglo['passwordConfirm']) && isset($arreglo['password']) && !$this->isEmpty($arreglo['password']) && !$this->isEmpty($arreglo['passwordConfirm']) && $arreglo['passwordConfirm'] != $arreglo['password']) {
                $result['error_msj'] = 'Las contraseñas ingresadas no coinciden.';
                return $result;
            }
        }
        $result['valido'] = TRUE;
        return $result;
    }

    /**
     * 
     */
    public function listAction() {
        $this->validateAccess(array('ADMINISTRADOR'));
        $view = new UsuarioView();
        return $view->renderListado(array(
                    'paginador' => array(
                        'tamanio_paginas' => ConfiguracionModel::getInstance()->getConfiguracion()->getPaginadoNumero(),
                        'pagina_actual' => (isset($_GET['pag']) ? $_GET['pag'] : 1),
                        'cant_paginas' => UsuarioModelOrm::getInstance()->getCantPaginas()
                    ),
                    'usuarios' => UsuarioModelOrm::getInstance()->getUsuariosPag((isset($_GET['pag']) ? $_GET['pag'] : 1)),
                    "config" => ConfiguracionModel::getInstance()->getConfiguracion(),
                    'user' => SessionController::getInstance()->getUsuarioAction()
        ));
    }

    /**
     * 
     */
    public function newAction() {
        $this->validateAccess(array('ADMINISTRADOR'));
        $view = new UsuarioView();
        return $view->renderNuevo(array(
                    "config" => ConfiguracionModel::getInstance()->getConfiguracion(),
                    'usuario_tipos' => RolModelOrm::getInstance()->getAll(),
                    'usuario_ubicacion' => UbicacionModelOrm::getInstance()->getAll(),
                    'user' => SessionController::getInstance()->getUsuarioAction()
        ));
    }

    /**
     * 
     */
    public function listOnlineAction() {
        $this->validateAccess(array('ADMINISTRADOR'));
        $view = new UsuarioView();
        return $view->renderListadoOnline(array(
                    'paginador' => array(
                        'tamanio_paginas' => ConfiguracionModel::getInstance()->getConfiguracion()->getPaginadoNumero(),
                        'pagina_actual' => (isset($_GET['pag']) ? $_GET['pag'] : 1),
                        'cant_paginas' => UsuarioModelOrm::getInstance()->getCantPaginasDesHab()
                    ),
                    'usuarios' => UsuarioModelOrm::getInstance()->getUsuariosDesHabPag((isset($_GET['pag']) ? $_GET['pag'] : 1)),
                    "config" => ConfiguracionModel::getInstance()->getConfiguracion(),
                    'user' => SessionController::getInstance()->getUsuarioAction()
        ));
    }

    /**
     * 
     */
    public function new_processAction() {
        $this->validateAccess(array('ADMINISTRADOR'));
        $view = new UsuarioView();
        $model = new UsuarioModelOrm();
        $usuario = new Usuario();
        $usuario->setApellido($_POST['apellido']);
        $usuario->setNombre($_POST['nombre']);
        $usuario->setDocumento($_POST['dni']);
        $usuario->setEmail($_POST['email']);
        $usuario->setUsuario($_POST['usuario']);
        $usuario->setTelefono("telefono");
        $usuario->setIdentificador(SessionController::getInstance()->sessionHash($_POST['usuario']));
        $usuario->setClave(SessionController::getInstance()->sessionHash($_POST['password']));
        $usuario->setRol(RolModelOrm::getInstance()->getById(intval($_POST['rol'])));
        if ($usuario->getRol()->getNombre() == 'USUARIO ONLINE') {
            $usuario->setUbicacion(UbicacionModelOrm::getInstance()->getById(intval($_POST['ubicacion'])));
        }
        $validacion = $this->validate($_POST);
        if (!$this->validateCsrf()) {//Validacion CSRF!!!***
            return NULL;
        }//*************************************************
        if (!$validacion['valido']) {
            return $view->renderNuevo(array(
                        'usuario' => $usuario,
                        "config" => ConfiguracionModel::getInstance()->getConfiguracion(),
                        'error_msj' => $validacion['error_msj'],
                        'usuario_tipos' => RolModelOrm::getInstance()->getAll(),
                        'usuario_ubicacion' => UbicacionModelOrm::getInstance()->getAll(),
                        'user' => SessionController::getInstance()->getUsuarioAction()
            ));
        }
        $model->insertEntity($usuario);
        return $view->renderListado(array(
                    'paginador' => array(
                        'pagina_actual' => 1,
                        'cant_paginas' => UsuarioModelOrm::getInstance()->getCantPaginas()
                    ),
                    "config" => ConfiguracionModel::getInstance()->getConfiguracion(),
                    'success_msj' => 'El Usuario fue registrado correctamente.',
                    'usuarios' => UsuarioModelOrm::getInstance()->getUsuariosPag(1),
                    'user' => SessionController::getInstance()->getUsuarioAction()
        ));
    }

    /**
     * 
     */
    public function newUserOnline() {
        $model = new UsuarioModelOrm();
        $usuario = new Usuario();
        $usuario->setApellido($_POST['apellido']);
        $usuario->setNombre($_POST['nombre']);
        $usuario->setDocumento($_POST['dni']);
        $usuario->setEmail($_POST['email']);
        $usuario->setUsuario($_POST['usuario']);
        $usuario->setTelefono("telefono");
        $usuario->setHabilitado(FALSE);
        $usuario->setIdentificador(SessionController::getInstance()->sessionHash($_POST['usuario']));
        $usuario->setClave(SessionController::getInstance()->sessionHash($_POST['password']));
        $usuario->setRol(RolModelOrm::getInstance()->getByNombre('USUARIO ONLINE'));
        $usuario->setUbicacion(UbicacionModelOrm::getInstance()->getById(intval($_POST['ubicacion'])));
        $validacion = $this->validate($_POST, 'online');
        if (!$validacion['valido']) {
            return array(
                'valido' => false,
                'error_msj' => $validacion['error_msj'],
                'usuario' => $usuario
            );
        }
        $model->insertEntity($usuario);
        return array(
            'valido' => true
        );
    }

    /**
     * 
     */
    public function editAction() {
        $this->validateAccess(array('ADMINISTRADOR'));
        if (!$this->isInteger($_GET['id']) || !($usuario = UsuarioModelOrm::getInstance()->getById($_GET['id']))) {//Si no es un ID valido o no se encuentra el usuario con ese ID muestra una pagina de error
            $this->goNotFound();
        }
        $view = new UsuarioView();
        return $view->renderEditar(array(
                    "config" => ConfiguracionModel::getInstance()->getConfiguracion(),
                    'usuario' => $usuario,
                    'user' => SessionController::getInstance()->getUsuarioAction(),
                    'usuario_tipos' => RolModelOrm::getInstance()->getAll(),
                    'usuario_ubicacion' => UbicacionModelOrm::getInstance()->getAll(),
        ));
    }

    /**
     * 
     */
    public function edit_processAction() {
        $this->validateAccess(array('ADMINISTRADOR'));
        $view = new UsuarioView();
        if (!$this->isInteger($_POST['id']) || !($usuario = UsuarioModelOrm::getInstance()->getById($_POST['id']))) {//Si no es un ID valido o no se encuentra el usuario con ese ID muestra una pagina de error
            $this->goNotFound();
        }
        $validacion = $this->validate($_POST, 'edit');
        if (!$this->validateCsrf()) {//Validacion CSRF!!!***
            return NULL;
        }//*************************************************
        if (!$validacion['valido']) {
            return $view->renderEditar(array(
                        'error_msj' => $validacion['error_msj'],
                        'usuario' => $usuario,
                        'usuario_tipos' => RolModelOrm::getInstance()->getAll(),
                        'usuario_ubicacion' => UbicacionModelOrm::getInstance()->getAll(),
                        'user' => SessionController::getInstance()->getUsuarioAction()
            ));
        }
        $model = new UsuarioModelOrm();
        $usuario->setApellido($_POST['apellido']);
        $usuario->setNombre($_POST['nombre']);
        $usuario->setDocumento($_POST['dni']);
        $usuario->setEmail($_POST['email']);
        $usuario->setHabilitado(filter_var($_POST['habilitado'], FILTER_VALIDATE_BOOLEAN, array('flags' => FILTER_NULL_ON_FAILURE)));
        $usuario->setUsuario($_POST['usuario']);
        $usuario->setTelefono($_POST['telefono']);
        $usuario->setIdentificador(SessionController::getInstance()->sessionHash($_POST['usuario']));
        $usuario->setRol(RolModelOrm::getInstance()->getById(intval($_POST['rol'])));
        if (isset($_POST['password']) && ($_POST['password'] != '')) {
            $usuario->setClave(SessionController::getInstance()->sessionHash($_POST['password']));
        }
        if ($usuario->getRol()->getNombre() == 'USUARIO ONLINE') {
            $usuario->setUbicacion(UbicacionModelOrm::getInstance()->getById(intval($_POST['ubicacion'])));
        }
        if (!$model->updateEntity($usuario)) {
            return $view->renderListado(array(
                        'error_msj' => 'No se pudo actualizar el usuario en el sistema.',
                        'user' => SessionController::getInstance()->getUsuarioAction()
            ));
        }
        return $view->renderListado(array(
                    'paginador' => array(
                        'pagina_actual' => 1,
                        'cant_paginas' => UsuarioModelOrm::getInstance()->getCantPaginas()
                    ),
                    'success_msj' => 'El Usuario fue actualizado correctamente.',
                    'usuarios' => UsuarioModelOrm::getInstance()->getUsuariosPag(1),
                    'user' => SessionController::getInstance()->getUsuarioAction()
        ));
    }

    /**
     * 
     */
    public function showAction() {
        $this->validateAccess(array('ADMINISTRADOR'));
        if (!$this->isInteger($_GET['id']) || !($usuario = UsuarioModelOrm::getInstance()->getById($_GET['id']))) {//Si no es un ID valido o no se encuentra el usuario con ese ID muestra una pagina de error
            $this->goNotFound();
        }
        $view = new UsuarioView();
        return $view->renderShow(array(
                    "config" => ConfiguracionModel::getInstance()->getConfiguracion(),
                    'usuario' => $usuario,
                    'user' => SessionController::getInstance()->getUsuarioAction()
        ));
    }

    /**
     * 
     */
    public function deleteAction() {
        $this->validateAccess(array('ADMINISTRADOR'));
        if (!$this->isInteger($_GET['id']) || !($usuario = UsuarioModelOrm::getInstance()->getById($_GET['id']))) {//Si no es un ID valido o no se encuentra el usuario con ese ID muestra una pagina de error
            $this->goNotFound();
        }
        $usuario->setBajaLogica(TRUE);
        if (!UsuarioModelOrm::getInstance()->updateEntity($usuario)) {
            return $view->renderListado(array(
                        'error_msj' => 'No se pudo eliminar el usuario del sistema.',
                        'user' => SessionController::getInstance()->getUsuarioAction()
            ));
        }
        $view = new UsuarioView();
        return $view->renderListado(array(
                    'paginador' => array(
                        'pagina_actual' => 1,
                        'cant_paginas' => UsuarioModelOrm::getInstance()->getCantPaginas()
                    ),
                    'success_msj' => 'El Usuario fue eliminado correctamente.',
                    'usuarios' => UsuarioModelOrm::getInstance()->getUsuariosPag(1),
                    'user' => SessionController::getInstance()->getUsuarioAction()
        ));
    }

}
