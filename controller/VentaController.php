<?php

require_once(PATH_CONTROLLER . 'Controller.php');
require_once(PATH_CLASS . 'IngresoDetalle.php');
require_once(PATH_CLASS . 'EgresoDetalle.php');
require_once(PATH_CLASS . 'Venta.php');
require_once(PATH_CLASS . 'TipoEgreso.php');
require_once(PATH_VIEW . 'VentaView.php');
require_once (PATH_MODEL . 'ProductoModel.php');
require_once (PATH_MODEL . 'IngresoTipoModelOrm.php');
require_once (PATH_MODEL . 'VentaModelOrm.php');
require_once (PATH_MODEL . 'DetalleEgresoModel.php');
require_once (PATH_MODEL . 'TipoEgresoModel.php');
require_once (PATH_MODEL . 'DetalleIngresoModelOrm.php');

class VentaController extends Controller {

    private static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function validate($arreglo) {
        if (!isset($arreglo['data']) || count($arreglo) < 1) {
            return false;
        }
        $detalles = $arreglo['data'];
        foreach ($detalles as $detalle) {
            
        }

        if (!isset($arreglo['cantidad']) || !$this->isInteger($arreglo['cantidad'])) {
            $result['error_msj'] = 'La cantidad no es un número válido o está en blanco.';
            return $result;
        }
        $result['valido'] = TRUE;
        return $result;
    }

    /**
     * 
     */
    public function newAction() {
        $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
        $view = new VentaView();
        return $view->renderNuevo(array(
                    "config" => ConfiguracionModel::getInstance()->getConfiguracion(),
                    'user' => SessionController::getInstance()->getUsuarioAction()
        ));
    }

    /**
     * 
     */
    public function listAction() {
        $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
        $view = new VentaView();
        return $view->renderListado(array(
                    'paginador' => array(
                        'tamanio_paginas' => ConfiguracionModel::getInstance()->getConfiguracion()->getPaginadoNumero(),
                        'pagina_actual' => (isset($_GET['pag']) ? $_GET['pag'] : 1),
                        'cant_paginas' => VentaModelOrm::getInstance()->getCantPaginas()
                    ),
                    'ventas' => VentaModelOrm::getInstance()->getVentasPag((isset($_GET['pag']) ? $_GET['pag'] : 1)),
                    "config" => ConfiguracionModel::getInstance()->getConfiguracion(),
                    'user' => SessionController::getInstance()->getUsuarioAction(),
        ));
    }

    /**
     * 
     */
    public function cancelAction() {
        if (!$this->hasAccess(array('ADMINISTRADOR', 'GESTION')) || !isset($_POST['id_venta']) || !$this->isInteger($_POST['id_venta']) || !$venta = VentaModelOrm::getInstance()->getById($_POST['id_venta'])) {
            $result['error'] = true;
            $result['error-acces'] = true;
            header('Content-Type: application/json');
            echo json_encode($result);
            die();
        }
        foreach ($venta->getDetalles() as $detalle) {
            ProductoModel::getInstance()->incrementarStock($detalle->getProducto()->getId(), $detalle->getCantidad());
            DetalleIngresoModelOrm::getInstance()->cancelIngreso($detalle);
        }
        VentaModelOrm::getInstance()->cancelVenta($venta);
        header('Content-Type: application/json');
        echo json_encode(array(
            'error' => false
        ));
    }

    /**
     * 
     */
    public function viewAction() {
        $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
        $view = new VentaView();
        return $view->renderVer(array(
                    "venta" => VentaModelOrm::getInstance()->getById($_GET['id']),
                    "config" => ConfiguracionModel::getInstance()->getConfiguracion(),
                    'user' => SessionController::getInstance()->getUsuarioAction()
        ));
    }

    /**
     * 
     */
    public function new_proccessAction() {
        if (!$this->hasAccess(array('ADMINISTRADOR', 'GESTION'))) {
            $result['error'] = true;
            $result['error-acces'] = true;
            header('Content-Type: application/json');
            echo json_encode($result);
            die();
        }
        if (!$this->validate($_POST)) {
            $result['error'] = true;
            $result['error-stock'] = true;
            header('Content-Type: application/json');
            echo json_encode($result);
            die();
        }
        $venta = new Venta();
        $venta->setEmpleado(SessionController::getInstance()->getUsuarioAction());
        $id_venta = VentaModelOrm::getInstance()->insertEntity($venta);
        $venta->setId($id_venta);
        $tipoIngreso = IngresoTipoModelOrm::getInstance()->getByNombre('VENTA MOSTRADOR');
        $detalles = $_POST['data'];
        $error = FALSE;
        foreach ($detalles as $detalle) {
            if (!$error) {
                $producto = ProductoModel::getInstance()->getByCodigoBarra($detalle['codigo']);
                if ($producto && ($producto->getStock() - $detalle['cantidad']) >= 0) { //Control de Stock
                    $newDetalle = new IngresoDetalle();
                    $newDetalle->setVenta($venta);
                    $newDetalle->setCantidad($detalle['cantidad']);
                    $newDetalle->setDescripcion($detalle['descripcion']);
                    $newDetalle->setPrecio_unitario($producto->getPrecioVentaUnitario());
                    $newDetalle->setProducto($producto);
                    $newDetalle->setTipo($tipoIngreso);
                    $venta->addDetalle($newDetalle);
                    DetalleIngresoModelOrm::getInstance()->insertEntity($newDetalle);
                    ProductoModel::getInstance()->decrementarStock($producto->getId(), $newDetalle->getCantidad());
                } else {
                    $error = TRUE;
                }
            }
        }
        if ($error) {
            foreach ($venta->getDetalles() as $detalle) {
                ProductoModel::getInstance()->incrementarStock($detalle->getProducto()->getId(), $detalle->getCantidad());
                DetalleIngresoModelOrm::getInstance()->deleteEntity($detalle);
            }
            VentaModelOrm::getInstance()->deleteEntity($venta);
            header('Content-Type: application/json');
            echo json_encode(array(
                'error' => true
            ));
        } else {
            header('Content-Type: application/json');
            echo json_encode(array(
                'error' => false
            ));
        }
    }

    /**
     * 
     */
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

}
