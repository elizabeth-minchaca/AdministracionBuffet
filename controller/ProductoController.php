<?php

require_once (PATH_VIEW . 'ProductoView.php');
require_once (PATH_VIEW . 'ErrorPageView.php');
require_once (PATH_VIEW . 'ErrorHandlerView.php');
require_once (PATH_MODEL . 'ProductoModel.php');
require_once (PATH_MODEL . 'CategoriaModel.php');
require_once (PATH_MODEL . 'ConfiguracionModel.php');
require_once (PATH_CONTROLLER . 'Controller.php');
require_once (PATH_CONTROLLER . 'ErrorHandlerController.php');
require_once (PATH_CONTROLLER . 'DefaultController.php');
require_once (PATH_CONTROLLER . 'SessionController.php');

class ProductoController extends Controller {

    private static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self ();
        }
        return self::$instance;
    }

	/**
	 * LISTADO DE PRODUCTOS ACTIVOS
	 * @param string $errormsj
	 * @param string $successmsj
	 * @return NULL
	 */
    public function listadoAction($errormsj = '', $successmsj = '') {
        $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
        $config = ConfiguracionModel::getInstance()->getConfiguracion();
        $user = SessionController::getInstance()->getUsuarioAction();
    	
        $view = new ProductoView();
    	return $view->renderListado(array(
    			'paginador' => array(
    					'tamanio_paginas' => ConfiguracionModel::getInstance()->getConfiguracion()->getPaginadoNumero(),
    					'pagina_actual' => (isset($_GET['pag']) ? $_GET['pag'] : 1),
    					'cant_paginas' => ProductoModel::getInstance()->getCantPaginas()
    			),
    			'productos' => ProductoModel::getInstance()->getProductosPag((isset($_GET['pag']) ? $_GET['pag'] : 1)),
    			"config" => $config,
    			'user' => $user,
    			'error_msj' => $errormsj,
    			'success_msj' => $successmsj   			 
    	));
    }
    
    /**
     * Nuevo Producto
     */
    public function nuevoAction() {
        $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
    	$config = ConfiguracionModel::getInstance()->getConfiguracion();
        $user = SessionController::getInstance()->getUsuarioAction();

        $categorias = CategoriaModel::getInstance()->getObjetosCategoria();
        $view = new ProductoView ();
        return $view->renderNuevo(array(
                    'categorias' => $categorias,
                    'user' => $user,
                    'config' => $config
                ));
    }

    public function verAction() {
        $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
    	$config = ConfiguracionModel::getInstance()->getConfiguracion();
        $user = SessionController::getInstance()->getUsuarioAction();

        if (isset($_GET ['id']) && $producto = ProductoModel::getInstance()->getById($_GET ['id'])) {
            $view = new ProductoView ();
            return $view->renderVer(array(
                        'producto' => $producto,
                        'user' => $user,
                        'config' => $config
                    ));
        } else {
            $error_msj = 'No se puede ver, no existe el producto en la BD';
            return $this->listadoAction($error_msj, '');
        }
    }

    public function editarAction() {
        $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
    	$config = ConfiguracionModel::getInstance()->getConfiguracion();
        $user = SessionController::getInstance()->getUsuarioAction();

        $view = new ProductoView ();
        if (isset($_GET ['id']) && $producto = ProductoModel::getInstance()->getById($_GET ['id'])) {
            $categorias = CategoriaModel::getInstance()->getObjetosCategoria();
            return $view->renderEditar(array(
                        'producto' => $producto,
                        'categorias' => $categorias,
                        'user' => $user,
                        'config' => $config
                    ));
        } else {
            $error_msj = 'No se puede editar,no existe el producto en la BD';
            return $this->listadoAction($error_msj, '');
        }
    }

    /**
     * NUEVO PRODUCTO
     */
    public function nuevoProductoSubmitAction() {
        $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
        
        if (!$this->validateCsrf()) {//Validacion CSRF!!!***
        	return NULL;
        }//*************************************************
       
    	$config = ConfiguracionModel::getInstance()->getConfiguracion();
        $user = SessionController::getInstance()->getUsuarioAction();

        $view = new ProductoView ();

        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fechaAlta = date("Y-m-d");
        
        //Validacion XSS!!********
        $_POST ['nombre'] = strip_tags($_POST ['nombre']);
        $_POST ['marca'] = strip_tags($_POST ['marca']);
        $_POST ['codigobarra'] = strip_tags($_POST ['codigobarra']);
        $_POST ['descripcion'] = strip_tags($_POST ['descripcion']);
        //************************
        
        $nombre = $_POST ['nombre'];
        $marca = $_POST ['marca'];
        $codigoBarra = $_POST ['codigobarra'];
        $stockMinimo = $_POST ['stockMinimo'];
        $idCategoria = $_POST ['categoria'];
        $precioVentaUnitario = $_POST ['precioVentaUnitario'];
        $descripcion = $_POST ['descripcion'];

        $validacion = $this->validate($_POST);
        if (!$validacion ['valido']) {
            $categorias = CategoriaModel::getInstance()->getObjetosCategoria();
            return $view->renderNuevo(array(
                        'error_msj' => $validacion ['error_msj'],
                        'categorias' => $categorias,
                        'nombre' => $nombre,
                        'marca' => $marca,
                        'codigoBarra' => $codigoBarra,
                        'stockMinimo' => $stockMinimo,
                        'idCategoria' => $idCategoria,
                        'precioVentaUnitario' => $precioVentaUnitario,
                        'descripcion' => $descripcion,
                        'user' => $user,
                        'config' => $config
                    ));
        }
        $param = array(
            'nombre' => $nombre,
            'marca' => $marca,
            'codigo_barra' => $codigoBarra,
            'stock_minimo' => $stockMinimo,
            'id_categoria' => $idCategoria,
            'precio_venta_unitario' => $precioVentaUnitario,
            'descripcion' => $descripcion,
            'fecha_alta' => $fechaAlta
        );
        if (!ProductoModel::getInstance()->existByCodigoBarra($codigoBarra)) {
            if (ProductoModel::getInstance()->nueva($param)) {
                $producto = ProductoModel::getInstance()->getByCodigoBarra($codigoBarra);
                $mensaje = 'El producto ' . $nombre . ' con codigo de barra ' . $codigoBarra . ' ha sido dado de alta';
                return $view->renderVer(array(
                            'producto' => $producto,
                            'success_msj' => $mensaje,
                            'user' => $user,
                            'config' => $config
                        ));
            } else {
                $mensaje = 'No se pudo realizar el alta, vuelva a ingresar los datos del nuevo producto';
                $categorias = CategoriaModel::getInstance()->getObjetosCategoria();
                return $view->renderNuevo(array(
                            'error_msj' => $mensaje,
                            'categorias' => $categorias,
                            'user' => $user,
                            'config' => $config
                        ));
            }
        } else {
            $mensaje = 'El producto con codigo de barra ' . $codigoBarra . ' ya existe, ingrese otro producto';
            $categorias = CategoriaModel::getInstance()->getObjetosCategoria();
            return $view->renderNuevo(array(
                        'error_msj' => $mensaje,
                        'categorias' => $categorias,
                        'user' => $user,
                        'config' => $config
                    ));
        }
    }

    /**
     * Modifica un producto
     */
    public function editarProductoSubmitAction() {
        $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
        
        if (!$this->validateCsrf()) {//Validacion CSRF!!!***
        	return NULL;
        }//*************************************************
                  
        
    	$config = ConfiguracionModel::getInstance()->getConfiguracion();
        $user = SessionController::getInstance()->getUsuarioAction();

        $view = new ProductoView ();

        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fechaAlta = date("Y-m-d");
        
        //Validacion XSS!!********
        $_POST ['nombre'] = strip_tags($_POST ['nombre']);
        $_POST ['marca'] = strip_tags($_POST ['marca']);
        $_POST ['codigobarra'] = strip_tags($_POST ['codigobarra']);
        $_POST ['descripcion'] = strip_tags($_POST ['descripcion']);
        //*************************
        
        $nombre = $_POST ['nombre'];
        $marca = $_POST ['marca'];
        $codigoBarra = $_POST ['codigobarra'];
        $stockMinimo = $_POST ['stockMinimo'];
        $idCategoria = $_POST ['categoria'];
        $precioVentaUnitario = $_POST ['precioVentaUnitario'];
        $descripcion = $_POST ['descripcion'];
        $id = $_POST ['idProducto'];

        $validacion = $this->validate($_POST);
        if (!$validacion ['valido']) {
            $categorias = CategoriaModel::getInstance()->getObjetosCategoria();
            return $view->renderEditar(array(
                        'error_msj' => $validacion ['error_msj'],
                        'categorias' => $categorias,
                        'nombre' => $nombre,
                        'marca' => $marca,
                        'codigoBarra' => $codigoBarra,
                        'stockMinimo' => $stockMinimo,
                        'idCategoria' => $idCategoria,
                        'precioVentaUnitario' => $precioVentaUnitario,
                        'descripcion' => $descripcion,
                        'idProducto' => $id,
                        'user' => $user,
                        'config' => $config
                    ));
        }
        $data = array(
            'nombre' => $nombre,
            'marca' => $marca,
            'stock_minimo' => $stockMinimo,
            'id_categoria' => $idCategoria,
            'precio_venta_unitario' => $precioVentaUnitario,
            'descripcion' => $descripcion,
            'fecha_alta' => $fechaAlta
        );
        if (ProductoModel::getInstance()->actualizar($id, $data)) {
            $producto = ProductoModel::getInstance()->getById($id);
            $mensaje = 'El producto ' . $nombre . ' con codigo de barra ' . $producto->getCodigoBarra() . ' ha sido modificado';
            return $view->renderVer(array(
                        'producto' => $producto,
                        'user' => $user,
                        'config' => $config,
                        'success_msj' => $mensaje
                    ));
        } else {
            $mensaje = 'No se pudo realizar la modificacion, vuelva a ingresar los nuevos datos del producto';
            $producto = ProductoModel::getInstance()->getById($id);
            return $view->renderEditar(array(
                        'producto' => $producto,
                        'categorias' => $categorias,
                        'user' => $user,
                        'config' => $config,
                        'error_msj' => $mensaje
                    ));
        }
    }

    /**
     * Eliminar Producto
     */
    public function eliminarAction() {
        $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
    	$config = ConfiguracionModel::getInstance()->getConfiguracion();
        $user = SessionController::getInstance()->getUsuarioAction();
        if (isset($_GET ['id']) && $resul = ProductoModel::getInstance()->getById($_GET ['id'])) {
        	if (ProductoModel::getInstance()->existeEnCompraPendiente($_GET["id"])) {
        		$error_msj = 'No se puede eliminar el producto, existen compras sin finalizar que lo referencian';
        		return $this->listadoAction($error_msj, '');
        	}
            ProductoModel::getInstance()->eliminarProducto($_GET ['id']);
            $success_msj = 'Se ha dado de baja el producto';
            return $this->listadoAction('', $success_msj);
        } else {
            $error_msj = 'No se puede dar de baja, no existe el producto en la BD';
            return $this->listadoAction($error_msj, '');
        }
    }
    
//     public function eliminarAction() {
//     	$this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
//     	$config = ConfiguracionModel::getInstance()->getConfiguracion();
//     	$user = SessionController::getInstance()->getUsuarioAction();
    	
//     	if (isset($_POST ['id']) && $prod = ProductoModel::getInstance()->getById($_POST ['id'])) {
//     		if (ProductoModel::getInstance()->existeEnCompraPendiente($_POST["id"])) {
//     			$error_msj = 'No se puede eliminar el producto, existen compras sin finalizar que lo referencian';
// //    			return $this->listadoAction($error_msj, '');
//     			header('Content-Type: application/json');
//     			echo json_encode(array(
//     					'error' => true,
//     					'msj' => $error_msj
//     			));
//     			die();
//     		}
//     		ProductoModel::getInstance()->eliminarProducto($_POST ['id']);
//     		$success_msj = 'Se ha dado de baja el producto';
// //    		return $this->listadoAction('', $success_msj);
//     		header('Content-Type: application/json');
//     		echo json_encode(array(
//     				'error' => false,
//     				'msj' => $success_msj,
//     				'id' => $prod->getId()
//     		));
//     		die();
    		
//     	} else {
//     		$error_msj = 'No se puede dar de baja, no existe el producto en la BD';
// //     		return $this->listadoAction($error_msj, '');
//     		header('Content-Type: application/json');
//     		echo json_encode(array(
//     				'error' => true,
//     				'msj' => $error_msj
//     		));
//     		die();    		
//     	}
//     }
    
    
    private function validate($arreglo) {
        $result ['valido'] = FALSE;
        if (!isset($arreglo ['nombre']) || $this->isEmpty(trim($arreglo ['nombre']))) {
            $result ['error_msj'] = 'El nombre no puede estar en blanco.';
            return $result;
        }
        if (!isset($arreglo ['marca']) || $this->isEmpty(trim($arreglo ['marca']))) {
            $result ['error_msj'] = 'La marca no puede estar en blanco.';
            return $result;
        }
        if (!isset($arreglo ['codigobarra']) || $this->isEmpty(trim($arreglo ['codigobarra']))) {
            $result ['error_msj'] = 'El codigo de barra no puede estar en blanco.';
            return $result;
        }
        if (!isset($arreglo ['stockMinimo']) || !$this->isInteger($arreglo ['stockMinimo']) || $arreglo ['stockMinimo'] < 1) {
            $result ['error_msj'] = 'El stock minimo no es un número válido o está en blanco.';
            return $result;
        }
        if (!isset($arreglo ['precioVentaUnitario']) || !$this->isFloat($arreglo ['precioVentaUnitario']) || $arreglo ['precioVentaUnitario'] < 1) {
            $result ['error_msj'] = 'El precio de venta no es un número válido o está en blanco.';
            return $result;
        }
        if (!isset($arreglo ['categoria']) || !$this->isInteger($arreglo ['categoria']) || $arreglo ['stockMinimo'] < 1) {
            $result ['error_msj'] = 'Debe seleccionar una categoria o está en blanco.';
            return $result;
        }
        if (!isset($arreglo ['descripcion']) || $this->isEmpty(trim($arreglo ['descripcion']))) {
            $result ['error_msj'] = 'La descripcion no puede estar en blanco.';
            return $result;
        }
        $result ['valido'] = TRUE;
        return $result;
    }

}
