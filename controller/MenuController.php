<?php

require_once (PATH_VIEW . 'MenuView.php');
require_once (PATH_MODEL . 'MenuModel.php');
require_once (PATH_MODEL . 'DetalleMenuModel.php');
require_once (PATH_MODEL . 'ProductoModel.php');
require_once (PATH_MODEL . 'ConfiguracionModel.php');
require_once (PATH_CONTROLLER . 'ErrorHandlerController.php');
require_once (PATH_CONTROLLER . 'Controller.php');
require_once(PATH_CONTROLLER . 'SessionController.php');
require_once (PATH_CONTROLLER . 'DefaultController.php');

class MenuController extends Controller {

    private static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self ();
        }
        return self::$instance;
    }

    public function editarMenuAction() {
        $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
        $config = ConfiguracionModel::getInstance()->getConfiguracion();
        $user = SessionController::getInstance()->getUsuarioAction();

        $menu = MenuModel::getInstance()->getById($_GET['id']);
        $view = new MenuView();
        return $view->renderEditarMenu(array(
                    'menu' => $menu,
                    'user' => $user,
                    'config' => $config
        ));
    }

    public function editarMenuDetalleAction($error_msj = '', $success_msj = '') {
        $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
        $config = ConfiguracionModel::getInstance()->getConfiguracion();
        $user = SessionController::getInstance()->getUsuarioAction();
        if (isset($_GET ['id']) && $menu = MenuModel::getInstance()->getById($_GET ['id'])) {
            if ($menu->getHabilitada()) {
                $error_msj = 'No se puede editar un menu habilitado';
                return $this->verMenuAction($error_msj, '');
            }
            $view = new MenuView ();
            return $view->renderEditarDetalleMenu(array(
                        'menu' => $menu,
                        'config' => $config,
                        'user' => $user,
                        'success_msj' => $success_msj,
                        'error_msj' => $error_msj
            ));
        } else {
            $error_msj = 'No se puede editar, no existe el menu en la BD';
            return $this->listadoMenuAction($error_msj, '');
        }
    }

    public function eliminarMenuAction() {
        $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
        if (!(isset($_POST ['id']) && $menu = MenuModel::getInstance()->getById($_POST ['id']))) {
        		$error_msj = 'No se puede eliminar el men&uacute;, no existe.';
        		header('Content-Type: application/json');
        		echo json_encode(array(
        				'error' => true,
        				'msj' => $error_msj
        		));
        		die();        	 
        }	
            if ($menu->getHabilitada()) {
                $error_msj = 'No se puede eliminar el men&uacute; de la fecha ' . $menu->getFechaFormateada() . ' porque ya se encuentra habilitada';
                header('Content-Type: application/json');
                echo json_encode(array(
                		'error' => true,
                		'msj' => $error_msj
                ));
                die();
            }
            foreach ($menu->getMenuDetalles() as $detalle) {
                DetalleMenuModel::getInstance()->eliminarDetalle($detalle->getId());
            }
            MenuModel::getInstance()->eliminar($menu->getId());
            // Mensaje de exito
            $success_msj = 'Se eliminó el men&uacute; de la fecha ' . $menu->getFechaFormateada();
            header('Content-Type: application/json');
            echo json_encode(array(
            		'error' => false,
            		'msj' => $success_msj
            ));
    }

    public function nuevoDetalleAction() {
        $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
        $config = ConfiguracionModel::getInstance()->getConfiguracion();
        $user = SessionController::getInstance()->getUsuarioAction();

        if (isset($_GET ['id']) && $menu = MenuModel::getInstance()->getById($_GET ['id'])) {
            $view = new MenuView ();
            if ($menu->getHabilitada()) {
                $error_msj = 'No se puede añadir un detalle asociado al menu de la fecha ' . $menu->getFechaFormateada() . ' porque ya se encuentra habilitada';
                return $view->renderVerMenu(array(
                            'menu' => $menu,
                            'config' => $config,
                            'user' => $user,
                            'error_msj' => $error_msj
                ));
            }
            return $view->renderNuevoDetalle(array(
                        "idMenu" => $_GET ['id'],
                        'menu' => $menu,
                        'config' => $config,
                        'user' => $user
            ));
        }
        $error_msj = 'No existe el menu en la BD';
        return $this->listadoMenuAction($error_msj, '');
    }

    public function eliminarDetalle() {
        $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
		if (! (isset ( $_POST ['id'] ) && $detalle = DetalleMenuModel::getInstance ()->getById ( $_POST ['id'] ))) {
			$error_msj = 'No se puede eliminar el detalle, no existe';
			header ( 'Content-Type: application/json' );
			echo json_encode ( array (
					'error' => true,
					'msj' => $error_msj 
			) );
			die ();
		}
		if ($detalle->getMenu ()->getHabilitada ()) {
			$error_msj = 'No se puede eliminar el detalle asociado al men&uacute; para la fecha ' . $detalle->getMenu ()->getFechaFormateada () . ' porque ya se encuentra habilitada';
			header ( 'Content-Type: application/json' );
			echo json_encode ( array (
					'error' => true,
					'msj' => $error_msj 
			) );
			die ();
		}
		$succes_msj = 'Se eliminó el detalle asociado al men&uacute; para la fecha ' . $detalle->getMenu ()->getFechaFormateada ();
		DetalleMenuModel::getInstance ()->eliminarDetalle ( $_POST ['id'] );
		$menu = MenuModel::getInstance ()->getById ( $detalle->getMenu ()->getId () );
		header ( 'Content-Type: application/json' );
		echo json_encode ( array (
				'error' => false,
				'msj' => $succes_msj,
				'id' => $menu->getId () 
		) );
    }


//     public function nuevoDetalleSubmitAction() {
//     	$this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
    
//     	if (!isset($_POST ['idMenu']) || !$menu = MenuModel::getInstance()->getById($_POST ['idMenu'])) {
//     		$error_msj = 'No existe el menu en la BD';
//     		return $this->listadoMenuAction($error_msj, '');
//     	}
//     	if ($menu->getHabilitada()){
//     		$error_msj = 'No se puede agregar detalles a un menu habilitado.';
//     		return $this->listadoMenuAction($error_msj, '');
//     	}
//     	$config = ConfiguracionModel::getInstance()->getConfiguracion();
//     	$user = SessionController::getInstance()->getUsuarioAction();
    	
//     	$validacion = $this->validateDetalle($_POST);
    	
//     	if (!$this->validateCsrf()) {//Validacion CSRF!!!***
//     		return NULL;
//     	}//*************************************************
    	   
//     	if (!$validacion['valido']) {
//     		$view = new MenuView();
//     		return $view->renderNuevoDetalle(array(
//     				"idMenu" => $_POST ['idMenu'],
//     				'config' => $config,
//     				'user' => $user,
//     				'error_msj' => $validacion['error_msj']
//     		));
//     	}
//     	$producto = ProductoModel::getInstance()->getByCodigoBarra($_POST['codigo']);
//     	if (!$producto) {
//     		$this->goNotFound();
//     	}
//     	$param = array(
//     			'id_menu' => $_POST ['idMenu'],
//     			'id_producto' => $producto->getId(),
//     			'cantidad' => $_POST ['cantidad'],
//     	);
//     	DetalleMenuModel::getInstance()->nueva($param);
//     	$_GET ['id'] = $_POST ['idMenu'];
//     	$success_msj = 'Se ha dado de alta un nuevo detalle al menu de la fecha '.$menu->getFechaFormateada();
//     	return $this->editarMenuDetalleAction('', $success_msj);
//     }
 
    public function nuevoDetalleSubmitAction() {
    	$this->validateAccess(array('ADMINISTRADOR', 'GESTION'));

    	//Validacion CSRF!!!***
    	if (!isset($_POST['token_csrf']) || SessionController::getInstance()->getTokenCSRF() != $_POST['token_csrf']) {
    		$error_msj = 'Ya caducó el formulario!';
    		header('Content-Type: application/json');
    		echo json_encode(array(
    				'error' => true,
    				'msj' => $error_msj,
    				'accessError' => true
    		));
    		die();
    	}//*************************************************
    	
    	//Validacion XSS!!********
    	$_POST['codigo'] = strip_tags($_POST['codigo']);
    	//*************************
    	   
    	   
    	
    	if (!isset($_POST ['idMenu']) || !$menu = MenuModel::getInstance()->getById($_POST ['idMenu'])) {
    		$error_msj = 'No se puede agregar el detalle porque no existe el men&uacute;';
    		header('Content-Type: application/json');
    		echo json_encode(array(
    				'error' => true,
    				'msj' => $error_msj,
    		));
    		die();
    	}
    	
    	if ($menu->getHabilitada()) {
    		$error_msj = 'No se puede agregar detalles a un men&uacute; habilitado.';
    		header('Content-Type: application/json');
    		echo json_encode(array(
    				'error' => true,
    				'msj' => $error_msj,
    				'id' => $menu->getId()
    		));
    		die();
    	}
    	
    	$validacion = $this->validateDetalle($_POST);
    	
    	if (!$validacion['valido']) {
    		header('Content-Type: application/json');
    		echo json_encode(array(
    				'error' => true,
    				'msj' => $validacion['error_msj'],
    				'id' => $menu->getId()
    		));
    		die();
    	}
    	$producto = ProductoModel::getInstance()->getByCodigoBarra($_POST['codigo']);
    	if ($producto->getStock() < $producto->getStockMinimo() ) {
    		$error_msj = "El stock actual es menor al stock m&iacute;nimo. Ingrese otro c&oacute;digo por favor!";
    		header('Content-Type: application/json');
    		echo json_encode(array(
    				'error' => true,
    				'msj' => $error_msj,
    				'id' => $menu->getId()
    		));
    		die();
    	}
    	$param = array(
    			'id_menu' => $menu->getId(),
    			'id_producto' => $producto->getId(),
    			'cantidad' => $_POST ['cantidad'],
    	);
    	DetalleMenuModel::getInstance()->nueva($param);
    	$success_msj = 'Se ha agregado un nuevo detalle al men&uacute; de la fecha ' . $menu->getFechaFormateada();
    	header('Content-Type: application/json');
    	echo json_encode(array(
    			'error' => false,
    			'msj' => $success_msj,
    			'id' => $menu->getId()
    	));
    }
    
    
    public function nuevoMenuAction() {
        $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
        $config = ConfiguracionModel::getInstance()->getConfiguracion();
        $user = SessionController::getInstance()->getUsuarioAction();

        $view = new MenuView ();
        return $view->renderNuevoMenu(array(
                    'config' => $config,
                    'user' => $user
        ));
    }


    public function nuevoMenuSubmitAction() {
    	$this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
    	$config = ConfiguracionModel::getInstance()->getConfiguracion();
    	$user = SessionController::getInstance()->getUsuarioAction();
    	$view = new MenuView();
    	
    	if (!$this->validateCsrf()) {//Validacion CSRF!!!***
    		return NULL;
    	}//*************************************************
    	
    	//Validacion XSS!!********
    	$_POST['fechaMenu'] = strip_tags($_POST['fechaMenu']); 
    	//************************* 
    	
     	$validacion = $this->validateMenu($_POST);
    	    	   
    	if (!$validacion ['valido']) {
    		return $view->renderNuevoMenu(array(
    				'error_msj' => $validacion ['error_msj'],
    				'user' => $user,
    				'config' => $config
    		));
    	}
   		$dateFecha= date("Y-m-d",strtotime(str_replace('/', '-', $_POST ['fechaMenu'])));
    	//verificar qu no haya fechas repetidas
    	if (MenuModel::getInstance()->existeFecha($dateFecha)){
    		$error_msj = 'No se pudo dar de alta el menu para la fecha porque ya existe, seleccione otra por favor';
    		return $view->renderNuevoMenu(array(
    				'error_msj' => $error_msj,
    				'user' => $user,
    				'config' => $config
    		));
    	}
    	$data = array(
    			"fecha" => $dateFecha,
    	);
    	$result = MenuModel::getInstance()->nueva($data);
    	if (!$result){
    		$error_msj = 'No se pudo dar de alta el menu para la fecha ' . $dateFecha;
    		return $this->listadoMenuAction($error_msj, '');
    	}
    	$success_msj = 'El menu; para la fecha ' . $dateFecha . ' ha sido dada de alta';
    	$_GET['id']= $result;
    	return $this->editarMenuDetalleAction('', $success_msj);
    }
    
//     public function editarMenuSubmitAction() {
//         $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
//         $id = $_POST['idMenu'];
//         if (!(isset($id) && $menu = MenuModel::getInstance()->getById($id))) {
//             $error_msj = 'No se puede editar el menu porque no existe en la BD';
//             header('Content-Type: application/json');
//             echo json_encode(array(
//             		'error' => true,
//             		'msj' => $error_msj
//             ));
//             die();            
//         }
//         if ($menu->getHabilitada()) {
//             $error_msj = 'No se puede editar el menu; de la fecha ' . $menu->getFechaFormateada() . ' porque ya se encuentra habilitada';
//             header('Content-Type: application/json');
//             echo json_encode(array(
//             		'error' => true,
//             		'msj' => $error_msj
//             ));
//             die();
//         }
//         $validacion = $this->validateMenu($_POST);
//         if (!$validacion ['valido']) {
//         	header('Content-Type: application/json');
//         	echo json_encode(array(
//         			'error' => true,
//         			'msj' => $validacion ['error_msj']
//         	));
//         	die();       	 
//         }
//         $dateFecha = date("Y-m-d", strtotime(str_replace('/', '-', $_POST ['fechaMenu'])));
//         if (($menu->getFechaFormateada() != $_POST['fechaMenu']) && ($menu2 = MenuModel::getInstance()->existeFecha($dateFecha)) && ($menu2->getFechaFormateada() != $menu->getFechaFormateada())) {
//             $error_msj = 'No se puede editar porque la fecha ya existe y no es igual al actual, seleccione otra por favor';
//             header('Content-Type: application/json');
//             echo json_encode(array(
//             		'error' => true,
//             		'msj' => $error_msj
//             ));
//             die();
//         }
//         $data = array(
//             "fecha" => $dateFecha
//         );
//         $result = MenuModel::getInstance()->actualizar($id, $data);
//         if (!$result) {
//             $error_msj = 'No se pudo modificar el menu para la fecha ' . $dateFecha;
//             header('Content-Type: application/json');
//             echo json_encode(array(
//             		'error' => true,
//             		'msj' => $error_msj
//             ));
//             die();
//         }
//         $success_msj = 'El menu para la fecha ' . $dateFecha . ' ha sido modificada';
//         header('Content-Type: application/json');
//         echo json_encode(array(
//         		'error' => false,
//         		'msj' => $success_msj,
//         		'id' => $id
//         ));       
//     }

    public function editarMenuSubmitAction() {
    	$this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
    	$config = ConfiguracionModel::getInstance()->getConfiguracion();
    	$user = SessionController::getInstance()->getUsuarioAction();
    	$view = new MenuView();
    	
    	if (!$this->validateCsrf()) {//Validacion CSRF!!!***
    		return NULL;
    	}//*************************************************
    	
    	//Validacion XSS!!********
    	$_POST['fechaMenu'] = strip_tags($_POST['fechaMenu']); 
    	//************************* 
    	
    	$id= $_POST['idMenu'];
    	$_GET['id'] = $id;
    	if (!(isset($id) && $menu = MenuModel::getInstance()->getById($id))) {
    		$error_msj = 'No se puede editar el menu porque no existe en la BD';
    		return $this->listadoMenuAction($error_msj, '');
    	}
    	if ($menu->getHabilitada()){
    		$error_msj = 'No se puede editar el menu; de la fecha '.$menu->getFechaFormateada().' porque ya se encuentra habilitada';
    		return $this->listadoMenuAction($error_msj, '');
    	}
    	$validacion = $this->validateMenu($_POST);
    	
    	if (!$validacion ['valido']) {
    		return $view->renderEditarMenu(array(
    				'error_msj' => $validacion ['error_msj'],
    				'menu' => MenuModel::getInstance()->getById($id),
    				'user' => $user,
    				'config' => $config
    		));
    	}
    	$dateFecha= date("Y-m-d",strtotime(str_replace('/', '-', $_POST ['fechaMenu'])));
    	if(($menu->getFechaFormateada() != $_POST['fechaMenu']) && ($menu2 = MenuModel::getInstance()->existeFecha($dateFecha)) &&  ($menu2->getFechaFormateada() != $menu->getFechaFormateada()) ){
    		$error_msj = 'No se puede editar porque la fecha ya existe y no es igual al actual, seleccione otra por favor';
    		return $view->renderEditarMenu(array(
    				'error_msj' => $error_msj,
    				'menu' => MenuModel::getInstance()->getById($id),
    				'user' => $user,
    				'config' => $config
    		));
    			
    	}
    	$data = array(
    			"fecha" => $dateFecha
    	);
    	$result = MenuModel::getInstance()->actualizar($id, $data);
    	if (!$result){
    		$error_msj = 'No se pudo modificar el menu para la fecha ' . $dateFecha;
    		return $this->listadoMenuAction($error_msj, '');
    	}
    	$_GET['id']= $id;
    	$success_msj = 'El menu para la fecha ' . $dateFecha . ' ha sido modificada';
    	return $this->editarMenuDetalleAction('', $success_msj);
    
    }
    
    /**
     * LISTADO DE MENÚ ACTIVAS
     * @param string $errormsj
     * @param string $successmsj
     * @return NULL
     */
    public function listadoMenuAction($errormsj = '', $successmsj = '') {
        $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
        $view = new MenuView();
        return $view->renderListadoMenus(array(
                    'paginador' => array(
                        'tamanio_paginas' => ConfiguracionModel::getInstance()->getConfiguracion()->getPaginadoNumero(),
                        'pagina_actual' => (isset($_GET['pag']) ? $_GET['pag'] : 1),
                        'cant_paginas' => MenuModel::getInstance()->getCantPaginas()
                    ),
                    'menus' => MenuModel::getInstance()->getMenuPag((isset($_GET['pag']) ? $_GET['pag'] : 1)),
                    "config" => ConfiguracionModel::getInstance()->getConfiguracion(),
                    'user' => SessionController::getInstance()->getUsuarioAction(),
                    'error_msj' => $errormsj,
                    'success_msj' => $successmsj
        ));
    }

    public function searchAction() {
        if (!$this->hasAccess(array('ADMINISTRADOR', 'GESTION'))) {
            header('Content-Type: application/json');
            echo json_encode(array('error' => 'No tiene acceso a la información requerida.'));
            die();
        }
        $producto = ProductoModel::getInstance()->getByCodigoBarra($_POST['codigo']);
        if (!$producto) {
            $result['encontrado'] = false;
        } else {
            $result['encontrado'] = true;
            $result['categoria'] = $producto->getCategoria()->verCadenaCategorias();
            $result['nombre'] = $producto->getNombre();
            $result['marca'] = $producto->getMarca();
            $result['precio'] = $producto->getPrecioVentaUnitario();
            $result['stock'] = $producto->getStock();
            $result['descripcion'] = $producto->getDescripcion();
        }
        header('Content-Type: application/json');
        echo json_encode($result);
    }

    public function verMenuAction($error_msj = '', $success_msj = '') {
        $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
        $config = ConfiguracionModel::getInstance()->getConfiguracion();
        $user = SessionController::getInstance()->getUsuarioAction();
        if (!(isset($_GET ['id']) && $menu = MenuModel::getInstance()->getById($_GET ['id']))) {
            $error_msj = 'No se puede ver, no existe el menu en la BD';
            return $this->listadoMenuAction($error_msj, '');
        }
        if (!$menu->getHabilitada()) {
            $error_msj = 'No se pueden ver los detalles de un menu deshabilitado';
            return $this->listadoMenuAction($error_msj, '');
        }
        $view = new MenuView ();
        return $view->renderVerMenu(array(
                    'menu' => $menu,
                    'config' => $config,
                    'user' => $user,
                    'success_msj' => $success_msj,
                    'error_msj' => $error_msj
                ));
    }

    private function validateMenu($arreglo) {
        $result['valido'] = FALSE;
        if (!isset($arreglo['fechaMenu']) || !$this->isDate(trim($arreglo['fechaMenu']))) {
            $result['error_msj'] = 'Debe seleccionar una fecha válidacon formato "dd/mm/aaaa" y no puede estar vacía.';
            return $result;
        }
        $result['valido'] = TRUE;
        return $result;
    }

    private function validateDetalle($arreglo) {
        $result['valido'] = FALSE;
        if (!isset($arreglo['codigo']) || $this->isEmpty($arreglo['codigo'])) {
            $result['error_msj'] = 'El código de producto no puede estar en blanco.';
            return $result;
        }
        if (!isset($arreglo['cantidad']) || !$this->isInteger($arreglo['cantidad']) || $arreglo['cantidad'] < 1) {
            $result['error_msj'] = "La cantidad no es un número válido o está en blanco.";
            return $result;
        }
        $result['valido'] = TRUE;
        return $result;
    }

    public function habilitarMenu() {
        $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
        if (!(isset($_POST ['id']) && $menu = MenuModel::getInstance()->getById($_POST ['id']))) {
        		$error_msj = 'No se puede habilitar el men&uacute;, no existe en la BD';
        		echo json_encode(array(
        				'error' => true,
        				'msj' => $error_msj
        		));
        		die();
        }
            if ($menu->getHabilitada()) {
                $error_msj = 'No se puede habilitar el men&uacute; para la fecha ' . $menu->getFechaFormateada() . ' porque ya se encuentra habilitada';
                header('Content-Type: application/json');
                echo json_encode(array(
                		'error' => true,
                		'msj' => $error_msj
                ));
                die();
            }
            if (count($menu->getMenuDetalles()) < 1) {
                $error_msj = 'Debe agregar al menos un producto para habilitar el men&uacute;';
                header('Content-Type: application/json');
                echo json_encode(array(
                		'error' => true,
                		'msj' => $error_msj
                ));
                die();              
            }
            MenuModel::getInstance()->habilitar($_POST["id"]);
            $success_msj = 'Se habilit&oacute; el men&uacute; para la fecha ' . $menu->getFechaFormateada();
            header('Content-Type: application/json');
            echo json_encode(array(
            		'error' => false,
            		'msj' => $success_msj,
            		'id' => $menu->getId()
            ));
            
    }

}