<?php

require_once (PATH_VIEW . 'CompraView.php');
require_once (PATH_MODEL . 'CompraModel.php');
require_once (PATH_MODEL . 'DetalleEgresoModel.php');
require_once (PATH_MODEL . 'ProductoModel.php');
require_once (PATH_MODEL . 'ProveedorModel.php');
require_once (PATH_MODEL . 'ConfiguracionModel.php');
require_once (PATH_MODEL . 'TipoEgresoModel.php');
require_once (PATH_VIEW . 'ErrorPageView.php');
require_once (PATH_CONTROLLER . 'ErrorHandlerController.php');
require_once (PATH_CONTROLLER . 'Controller.php');
require_once(PATH_CONTROLLER . 'SessionController.php');
require_once (PATH_CONTROLLER . 'DefaultController.php');

class CompraController extends Controller {

    private static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self ();
        }
        return self::$instance;
    }

    public function finalizarCompra() {
        $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
        if (!(isset($_POST ['id']) && $compra = CompraModel::getInstance()->getById($_POST ['id']))) {
        	$error_msj = 'No se puede finalizar la compra, no existe.';
        	header ( 'Content-Type: application/json' );
        	echo json_encode ( array (
        			'error' => true,
        			'msj' => $error_msj,
        	) );
        	die ();
        }	
            if ($compra->getFinalizada()) {
                $error_msj = 'No se puede finalizar la compra con fecha ' . $compra->getFechaFormateada() . ' y con proveedor ' . $compra->getProveedor()->getNombre() . ' - ' . $compra->getProveedor()->getCuit() . ' porque ya se encuentra finalizada';
                header ( 'Content-Type: application/json' );
                echo json_encode ( array (
                		'error' => true,
                		'msj' => $error_msj
                ) );
                die ();                
            }
            if (count($compra->getEgresosDetalles()) < 1) {
                $error_msj = 'Debe ingresar al menos un detalle egreso';
                header ( 'Content-Type: application/json' );
                echo json_encode ( array (
                		'error' => true,
                		'msj' => $error_msj
                ) );
                die ();
            }
            foreach ($compra->getEgresosDetalles() as $detalle) {
                $idProducto = $detalle->getProducto()->getId();
                ProductoModel::getInstance()->incrementarStock($idProducto, $detalle->getCantidad());
            }
            CompraModel::getInstance()->finalizarCompra($_POST["id"]);
            $success_msj = 'Se finalizó la compra con fecha ' . $compra->getFechaFormateada() . ' y con proveedor ' . $compra->getProveedor()->getNombre() . ' - ' . $compra->getProveedor()->getCuit();
            header ( 'Content-Type: application/json' );
            echo json_encode ( array (
            		'error' => false,
            		'msj' => $success_msj,
            		'id' => $compra->getId()
            ) );           
    }

    public function editarCompraAction() {
        $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
        $config = ConfiguracionModel::getInstance()->getConfiguracion();
        $user = SessionController::getInstance()->getUsuarioAction();

        $compra = CompraModel::getInstance()->getById($_GET['id']);
        $proveedores = ProveedorModel::getInstance()->getObjetosProveedores();

        $view = new CompraView();
        return $view->renderEditarCompra(array(
                    'compra' => $compra,
                    'proveedores' => $proveedores,
                    'user' => $user,
                    'config' => $config
        ));
    }

    public function editarCompraDetalleEgresoAction($error_msj = '', $success_msj = '') {
        $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
        $config = ConfiguracionModel::getInstance()->getConfiguracion();
        $user = SessionController::getInstance()->getUsuarioAction();
        if (isset($_GET ['id']) && $compra = CompraModel::getInstance()->getById($_GET ['id'])) {
            if ($compra->getFinalizada()) {
                $error_msj = 'No se puede editar una compra finalizada';
                return $this->verCompraAction($error_msj, '');
            }
            $view = new CompraView ();
            return $view->renderEditarCompraDetalleEgreso(array(
                        'compra' => $compra,
                        'config' => $config,
                        'user' => $user,
                        'success_msj' => $success_msj,
                        'error_msj' => $error_msj
            ));
        } else {
            $error_msj = 'No se puede editar, no existe la compra en la BD';
            return $this->listadoComprasAction($error_msj, '');
        }
    }

    public function eliminarCompraAction() {
        $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
        if (!(isset($_POST ['id']) && $compra = CompraModel::getInstance()->getById($_POST ['id']))) {
        		$error_msj = 'No se puede eliminar, la compra no existe.';
        		header ( 'Content-Type: application/json' );
        		echo json_encode ( array (
        				'error' => true,
        				'msj' => $error_msj
        		) );
        		die ();        		
        }	
            if ($compra->getFinalizada()) {
                $error_msj = 'No se puede eliminar la compra con fecha ' . $compra->getFechaFormateada() . ' y con proveedor ' . $compra->getProveedor()->getNombre() . ' - ' . $compra->getProveedor()->getCuit() . ' porque ya se encuentra finalizada';
                header ( 'Content-Type: application/json' );
                echo json_encode ( array (
                		'error' => true,
                		'msj' => $error_msj
                ) );
                die ();                
            }
            foreach ($compra->getEgresosDetalles() as $detalle) {
                DetalleEgresoModel::getInstance()->eliminarDetalle($detalle->getId());
            }
            CompraModel::getInstance()->eliminarCompra($compra->getId());
            // Mensaje de exito
            $success_msj = 'Se eliminó la compra con fecha ' . $compra->getFechaFormateada() . ' y con proveedor ' . $compra->getProveedor()->getNombre() . ' - ' . $compra->getProveedor()->getCuit();
            header ( 'Content-Type: application/json' );
            echo json_encode ( array (
            		'error' => false,
            		'msj' => $success_msj,
            ) );
    }

    public function nuevoEgresoDetalle() {
        $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
        $config = ConfiguracionModel::getInstance()->getConfiguracion();
        $user = SessionController::getInstance()->getUsuarioAction();

        if (isset($_GET ['id']) && $compra = CompraModel::getInstance()->getById($_GET ['id'])) {
            $view = new CompraView ();
            if ($compra->getFinalizada()) {
                $error_msj = 'No se puede añadir un detalle asociado a la compra con fecha ' . $compra->getFechaFormateada() . ' y con proveedor ' . $compra->getProveedor()->getNombre() . ' - ' . $compra->getProveedor()->getCuit() . ' porque ya se encuentra finalizada';
                return $view->renderVerCompra(array(
                            'compra' => $compra,
                            'config' => $config,
                            'user' => $user,
                            'error_msj' => $error_msj
                ));
            }
            return $view->renderNuevoEgresoDetalle(array(
                        "idCompra" => $_GET ['id'],
                        'config' => $config,
                        'user' => $user
            ));
        }
        $error_msj = 'No existe la compra en la BD';
        return $this->listadoComprasAction($error_msj, '');
    }

    public function eliminarEgresoDetalle() {
		$this->validateAccess ( array (	'ADMINISTRADOR', 'GESTION' ) );		
		if (!((isset($_POST ['id'])) && $detalle = DetalleEgresoModel::getInstance ()->getById ( $_POST ['id'] ))) {
			$error_msj = "No se puede eliminar el detalle de egreso, no existe";
			header ( 'Content-Type: application/json' );
			echo json_encode ( array (
					'error' => true,
					'msj' => $error_msj 
			) );
			die ();
		}
		if ($detalle->getCompra ()->getFinalizada ()) {
			$error_msj = 'No se puede eliminar el detalle asociado a la compra con fecha ' . $detalle->getCompra ()->getFecha () . ' y con proveedor ' . $detalle->getCompra ()->getProveedor ()->getNombre () . ' - ' . $detalle->getCompra ()->getProveedor ()->getCuit () . ' porque ya se encuentra finalizada';
			header ( 'Content-Type: application/json' );
			echo json_encode ( array (
					'error' => true,
					'msj' => $error_msj 
			) );
			die ();
		}
		DetalleEgresoModel::getInstance ()->eliminarDetalle ( $_POST ['id'] );
		$compra = CompraModel::getInstance ()->getById ( $detalle->getCompra ()->getId () );
		$succes_msj = "Se eliminó el detalle asociado a la compra con Fecha " . $detalle->getCompra ()->getFechaFormateada() . " del Proveedor " . $detalle->getCompra ()->getProveedor ()->getNombre () . " con CUIT " . $detalle->getCompra ()->getProveedor ()->getCuit ();
		header ( 'Content-Type: application/json' );
		echo json_encode ( array (
				'error' => false,
				'msj' => $succes_msj,
				'id' => $compra->getId()
		) );
    }

    public function nuevaDetalleEgresoSubmitAction() {
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
        
        if (!isset($_POST ['idCompra']) || !$compra = CompraModel::getInstance()->getById($_POST ['idCompra'])) {
            $error_msj = 'No se puede agregar el detalle, no existe la compra en la BD';
            header('Content-Type: application/json');
            echo json_encode(array(
            		'error' => true,
            		'msj' => $error_msj,
            ));
            die();
        }
        if ($compra->getFinalizada()) {
            $error_msj = 'No se puede agregar detalles de egreso a una compra finalizada.';
            header('Content-Type: application/json');
            echo json_encode(array(
            		'error' => true,
            		'msj' => $error_msj,
            		'id' => $compra->getId()
            ));
            die();
        }
        
        $validacion = $this->validateDetalleEgreso($_POST);        
        if (!$validacion['valido']) {
        	header('Content-Type: application/json');
        	echo json_encode(array(
        			'error' => true,
        			'msj' => $validacion['error_msj'],
        			'id' => $compra->getId()
        	));
        	die();      	 
        }
        $tipoEgreso = TipoEgresoModel::getInstance()->getByNombre('COMPRA A PROVEEDOR');
        $producto = ProductoModel::getInstance()->getByCodigoBarra($_POST['codigo']);
        if (!$tipoEgreso || !$producto) {
            $this->goNotFound();
        }
        $param = array(
            'id_compra' => $_POST ['idCompra'],
            'id_producto' => $producto->getId(),
            'cantidad' => $_POST ['cantidad'],
            'precio_unitario' => $_POST ['precioUnitario'],
            'id_tipo_egreso' => $tipoEgreso->getId(),
            'fecha' => date("Y-m-d H:i:s")
        );
        DetalleEgresoModel::getInstance()->nueva($param);
        $success_msj = 'Se ha dado de alta un nuevo detalle de egreso a la compra de la fecha ' . $compra->getFechaFormateada();        
        header('Content-Type: application/json');
        echo json_encode(array(
        		'error' => false,
        		'msj' => $success_msj,
        		'id' => $compra->getId()
        ));
	}

    public function nuevaCompraAction() {
        $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
        $config = ConfiguracionModel::getInstance()->getConfiguracion();
        $user = SessionController::getInstance()->getUsuarioAction();

        $proveedores = ProveedorModel::getInstance()->getObjetosProveedores();
        $view = new CompraView ();
        return $view->renderNuevaCompra(array(
                    'proveedores' => $proveedores,
                    'config' => $config,
                    'user' => $user
        ));
    }

//     public function nuevaCompraSubmitAction() {
//         $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
//         $validacion = $this->validateCompra($_POST);
//         if (!$validacion ['valido']) {
//         	header('Content-Type: application/json');
//         	echo json_encode(array(
//         			'error' => true,
//         			'msj' => $validacion['error_msj'],
//         	));
//         	die();        	 
//         }
//         $dateFecha = date("Y-m-d", strtotime(str_replace('/', '-', $_POST ['fechaCompra'])));
//         $data = array(
//             "id_proveedor" => $_POST ['idProveedor'],
//             "fecha" => $dateFecha,
//             "factura" => file_get_contents($_FILES ['factura'] ['tmp_name'])
//         );
//         $result = CompraModel::getInstance()->nueva($data);
//         if (!$result) {
//             $error_msj = 'No se pudo dar de alta la compra con fecha ' . $dateFecha;
//             header('Content-Type: application/json');
//             echo json_encode(array(
//             		'error' => true,
//             		'msj' => $validacion['error_msj'],
//             ));
//             die();
//         }
//         $success_msj = 'La compra con fecha ' . $dateFecha . ' ha sido dada de alta';
//         header('Content-Type: application/json');
//         echo json_encode(array(
//         		'error' => false,
//         		'msj' => $success_msj,
//         		'id' => $result
//         ));
//     }

//     public function editarCompraSubmitAction() {
//         $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
//         $id = $_POST['idCompra'];
//         if (!(isset($id) && $compra = CompraModel::getInstance()->getById($id))) {
//             $error_msj = 'No se puede editar la compra porque no existe en la BD';
//             header ( 'Content-Type: application/json' );
//             echo json_encode ( array (
//             		'error' => true,
//             		'msj' => $error_msj
//             ) );
//             die ();
//         }
//         if ($compra->getFinalizada()) {
//             $error_msj = 'No se puede editar la compra con fecha ' . $compra->getFechaFormateada() . ' y con proveedor ' . $compra->getProveedor()->getNombre() . ' - ' . $compra->getProveedor()->getCuit() . ' porque ya se encuentra finalizada';
//             header ( 'Content-Type: application/json' );
//             echo json_encode ( array (
//             		'error' => true,
//             		'msj' => $error_msj
//             ) );
//             die ();            
//         }
//         $validacion = $this->validateCompraSinFactura($_POST);
//         if (!$validacion ['valido']) {
//         	header ( 'Content-Type: application/json' );
//         	echo json_encode ( array (
//         			'error' => true,
//         			'msj' => $validacion['error_msj']
//         	) );
//         	die ();        	 
//         }
//         $dateFecha = date("Y-m-d", strtotime(str_replace('/', '-', $_POST ['fechaCompra'])));
//         if ($_FILES['factura']['error'] != 0) {
//             $data = array(
//                 "id_proveedor" => $_POST ['idProveedor'],
//                 "fecha" => $dateFecha
//             );
//             if (!CompraModel::getInstance()->actualizar($id, $data)) {
//                 $error_msj = 'No se pudo modificar la compra con fecha ' . $dateFecha;
//                 header ( 'Content-Type: application/json' );
//                 echo json_encode ( array (
//                 		'error' => true,
//                 		'msj' => $error_msj
//                 ) );
//                 die ();
//             }
//             // Mensaje exito
//             $success_msj = 'La compra con fecha ' . $dateFecha . ' ha sido modificada';
//             header ( 'Content-Type: application/json' );
//             echo json_encode ( array (
//             		'error' => false,
//             		'msj' => $success_msj,
//             		'id' => $id
//             ) );
//             die ();           
//         } else {
//             $data = array(
//                 "id_proveedor" => $_POST ['idProveedor'],
//                 "fecha" => $dateFecha,
//                 "factura" => file_get_contents($_FILES ['factura']['tmp_name'])
//             );
//             if (!CompraModel::getInstance()->actualizar($id, $data)) {
//                 $error_msj = 'No se pudo modificar la compra con fecha' . $dateFecha;
//                 header ( 'Content-Type: application/json' );
//                 echo json_encode ( array (
//                 		'error' => true,
//                 		'msj' => $error_msj
//                 ) );
//                 die ();                
//             }
//             // Mensaje exito
//             $success_msj = 'La compra con fecha ' . $dateFecha . ' ha sido modificada';
//             header ( 'Content-Type: application/json' );
//             echo json_encode ( array (
//             		'error' => false,
//             		'msj' => $success_msj,
//             		'id' => $id
//             ) );            
//         }
//     }

    public function nuevaCompraSubmitAction() {
    	$this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
    	$config = ConfiguracionModel::getInstance()->getConfiguracion();
    	$user = SessionController::getInstance()->getUsuarioAction();
    	
    	//Validacion XSS!!********
    	$_POST ['fechaCompra'] = strip_tags($_POST ['fechaCompra']);
    	//*************************
    	   
    	if (!$this->validateCsrf()) {//Validacion CSRF!!!***
    		return NULL;
    	}//*************************************************
    	   
    	$validacion = $this->validateCompra($_POST);
    	if (!$validacion ['valido']) {
    		$view = new CompraView();
    		$proveedores = ProveedorModel::getInstance()->getObjetosProveedores();
    		$factura=file_get_contents($_FILES ['factura']['tmp_name']);
    		return $view->renderNuevaCompra(array(
    				'error_msj' => $validacion ['error_msj'],
    				'proveedores' => $proveedores,
    				'idProveedor' => $_POST['idProveedor'],
    				'fechaCompra' => $_POST['fechaCompra'],
    				'factura' => $factura,
    				'user' => $user,
    				'config' => $config
    		));
    	}
    	$dateFecha= date("Y-m-d",strtotime(str_replace('/', '-', $_POST ['fechaCompra'])));
    	$data = array(
    			"id_proveedor" => $_POST ['idProveedor'],
    			"fecha" => $dateFecha,
    			"factura" => file_get_contents($_FILES ['factura'] ['tmp_name'])
    	);
    	$result = CompraModel::getInstance()->nueva($data);
    	if (!$result){
    		$error_msj = 'No se pudo dar de alta la compra con fecha ' . $dateFecha;
    		return $this->listadoComprasAction($error_msj, '');
    	}
    	$success_msj = 'La compra con fecha ' . $dateFecha . ' ha sido dada de alta';
    	$_GET['id']= $result;
    	return $this->editarCompraDetalleEgresoAction('', $success_msj);
    }
    
    public function editarCompraSubmitAction() {
    	$this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
    	$config = ConfiguracionModel::getInstance()->getConfiguracion();
    	$user = SessionController::getInstance()->getUsuarioAction();
    	$view = new CompraView();
    	
    	$proveedores = ProveedorModel::getInstance()->getObjetosProveedores();
    	if (!$this->validateCsrf()) {//Validacion CSRF!!!***
    		return NULL;
    	}//*************************************************
    	   
    	//Validacion XSS!!********
    	$_POST ['fechaCompra'] = strip_tags($_POST ['fechaCompra']);
    	//*************************
    	   
    	$id= $_POST['idCompra'];
    	$_GET['id'] = $id;
    	if (!(isset($id) && $compra = CompraModel::getInstance()->getById($id))) {
    		$error_msj = 'No se puede editar la compra porque no existe en la BD';
    		return $this->listadoComprasAction($error_msj, '');
    	}
    	if ($compra->getFinalizada()){
    		$error_msj = 'No se puede editar la compra con fecha '.$compra->getFechaFormateada().' y con proveedor '.$compra->getProveedor()->getNombre() .' - '.$compra->getProveedor()->getCuit() .' porque ya se encuentra finalizada';
    		return $this->listadoComprasAction($error_msj, '');
    	}
    	 
    	$validacion = $this->validateCompraSinFactura($_POST);
    	   
    	if (!$validacion ['valido']) {
    		return $view->renderEditarCompra(array(
    				'error_msj' => $validacion ['error_msj'],
    				'proveedores' => ProveedorModel::getInstance()->getObjetosProveedores(),
    				'compra' => CompraModel::getInstance()->getById($id),
    				'user' => $user,
    				'config' => $config
    		));
    	}
    	$dateFecha= date("Y-m-d",strtotime(str_replace('/', '-', $_POST ['fechaCompra'])));
    	if ($_FILES['factura']['error'] != 0) {
    		$data = array(
    				"id_proveedor" => $_POST ['idProveedor'],
    				"fecha" => $dateFecha
    		);
    		if (!CompraModel::getInstance()->actualizar($id, $data)) {
    			$error_msj = 'No se pudo modificar la compra con fecha '.$dateFecha;
    			return $this->editarCompraDetalleEgresoAction($error_msj, '');
    		}
    		// Mensaje exito
    		$success_msj = 'La compra con fecha ' . $dateFecha . ' ha sido modificada';
    		return $this->editarCompraDetalleEgresoAction('', $success_msj);
    	}else {
    		$data = array(
    				"id_proveedor" => $_POST ['idProveedor'],
    				"fecha" => $dateFecha,
    				"factura" => file_get_contents($_FILES ['factura']['tmp_name'])
    		);
    		if (!CompraModel::getInstance()->actualizar($id, $data)) {
    			$error_msj = 'No se pudo modificar la compra con fecha'.$dateFecha;
    			return $this->editarCompraDetalleEgresoAction($error_msj, '');
    		}
    		// Mensaje exito
    		$success_msj = 'La compra con fecha ' . $dateFecha . ' ha sido modificada';
    		return $this->editarCompraDetalleEgresoAction('', $success_msj);
    	}
    }
    
    
    /**
     * LISTADO DE COMPRAS ACTIVAS
     * @param string $errormsj
     * @param string $successmsj
     * @return NULL
     */
    public function listadoComprasAction($errormsj = '', $successmsj = '') {
        $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
        $config = ConfiguracionModel::getInstance()->getConfiguracion();
        $user = SessionController::getInstance()->getUsuarioAction();
        $view = new CompraView();
        return $view->renderListadoCompras(array(
                    'paginador' => array(
                        'tamanio_paginas' => ConfiguracionModel::getInstance()->getConfiguracion()->getPaginadoNumero(),
                        'pagina_actual' => (isset($_GET['pag']) ? $_GET['pag'] : 1),
                        'cant_paginas' => CompraModel::getInstance()->getCantPaginas()
                    ),
                    'compras' => CompraModel::getInstance()->getComprasPag((isset($_GET['pag']) ? $_GET['pag'] : 1)),
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

    public function verCompraAction($error_msj = '', $success_msj = '') {
        $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
        $config = ConfiguracionModel::getInstance()->getConfiguracion();
        $user = SessionController::getInstance()->getUsuarioAction();
        if (!(isset($_GET ['id']) && $compra = CompraModel::getInstance()->getById($_GET ['id']))) {
            $error_msj = 'No se puede ver, no existe la compra en la BD';
            return $this->listadoComprasAction($error_msj, '');
        }
        if (!$compra->getFinalizada()) {
            $error_msj = 'No se pueden ver los detalles de una compra pendiente';
            return $this->listadoComprasAction($error_msj, '');
        }
        $view = new CompraView ();
        return $view->renderVerCompra(array(
                    'compra' => $compra,
                    'config' => $config,
                    'user' => $user,
                    'success_msj' => $success_msj,
                    'error_msj' => $error_msj
        ));
    }

    private function validateCompraSinFactura($arreglo) {
        $result['valido'] = FALSE;
        if (!isset($arreglo['idProveedor']) || $this->isEmpty($arreglo['idProveedor'])) {
            $result['error_msj'] = 'Debe seleccionar un proveedor, no puede estar en blanco.';
            return $result;
        }
        if (!isset($arreglo['fechaCompra']) || !$this->isDate(trim($arreglo['fechaCompra']))) {
            $result['error_msj'] = 'Debe seleccionar una fecha válidacon formato "dd/mm/aaaa" y no puede estar vacía.';
            return $result;
        }
        $result['valido'] = TRUE;
        return $result;
    }

    private function validateCompra($arreglo) {
        $result['valido'] = FALSE;
        if (!isset($arreglo['idProveedor']) || $this->isEmpty($arreglo['idProveedor'])) {
            $result['error_msj'] = 'Debe seleccionar un proveedor, no puede estar en blanco.';
            return $result;
        }
        if (!isset($arreglo['fechaCompra']) || !$this->isDate(trim($arreglo['fechaCompra']))) {
            $result['error_msj'] = 'Debe seleccionar una fecha válida con formato "dd/mm/aaaa" y no puede estar vacía.';
            return $result;
        }
        if ($_FILES['factura']['error'] != 0) {
            $result['error_msj'] = "Debe ingresar una factura escaneada, no puede estar vacía.";
            return $result;
        }
        $result['valido'] = TRUE;
        return $result;
    }

    private function validateDetalleEgreso($arreglo) {
        $result['valido'] = FALSE;
        if (!isset($arreglo['codigo']) || $this->isEmpty($arreglo['codigo'])) {
            $result['error_msj'] = 'El código de producto no puede estar en blanco.';
            return $result;
        }
        if (!isset($arreglo['cantidad']) || !$this->isInteger($arreglo['cantidad']) || $arreglo['cantidad'] < 1) {
            $result['error_msj'] = "La cantidad no es un número válido o está en blanco.";
            return $result;
        }
        if (!isset($arreglo['precioUnitario']) || !$this->isFloat($arreglo['precioUnitario']) || $arreglo['precioUnitario'] < 1) {
            $result["error_msj"] = "El precio unitario no es un número válido o está en blanco.";
            return $result;
        }
        $result['valido'] = TRUE;
        return $result;
    }

    public function descargarFactura() {
        $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
        if (!(isset($_GET ['id']) && $compra = CompraModel::getInstance()->getById($_GET ['id']))) {
            $error_msj = 'No se puede ver, no existe la compra en la BD';
            return $this->listadoComprasAction($error_msj, '');
        }
        echo '<img class="img-thumbnail" src="data:image/png;base64,' . $compra->getFactura() . '" alt="Factura Escaneada" />';
    }

}
