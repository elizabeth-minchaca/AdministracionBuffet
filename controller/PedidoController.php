<?php

require_once(PATH_CONTROLLER . 'Controller.php');
require_once(PATH_VIEW . 'PedidoView.php');
require_once (PATH_MODEL . 'MenuModel.php');
require_once (PATH_MODEL . 'DetalleMenuModel.php');
require_once (PATH_MODEL . 'ProductoModel.php');
require_once (PATH_MODEL . 'PedidoEstadoModelOrm.php');
require_once (PATH_MODEL . 'PedidoModelOrm.php');
require_once (PATH_MODEL . 'VentaModelOrm.php');
require_once (PATH_MODEL . 'PedidoDetalleModelOrm.php');
require_once(PATH_CONTROLLER . 'PruebasBotController.php');
require_once(PATH_CLASS . 'Menu.php');
require_once(PATH_CLASS . 'Venta.php');
require_once(PATH_CLASS . 'IngresoDetalle.php');
require_once(PATH_CLASS . 'PedidoDetalle.php');
require_once(PATH_CLASS . 'Pedido.php');

class PedidoController extends Controller {

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
    public function newAction() {
        $this->validateAccess(array('USUARIO ONLINE'));
        $view = new PedidoView();
        $menuDelDia = MenuModel::getInstance()->getMenuDelDia();
        return $view->renderNuevo(array(
                    "config" => ConfiguracionModel::getInstance()->getConfiguracion(),
                    'user' => SessionController::getInstance()->getUsuarioAction(),
                    'menu' => PruebasBotController::getInstance()->menuDelDia()
        ));
    }

    /**
     * 
     */
    private function validatePedido($array, Menu $menu) {
        $valid = true;
        foreach ($array as $value) {
            $producto = ProductoModel::getInstance()->getById((int) $value['producto_id']);
            if (!$producto || !$menu->isProducto($producto->getId()) || ($producto->getStock() - $value['cantidad'] < 0)) {
                $valid = false;
            }
        }
        return $valid;
    }

    /**
     * 
     */
    public function myOrdersAction() {
        $this->validateAccess(array('USUARIO ONLINE'));
        $view = new PedidoView();
        $user = SessionController::getInstance()->getUsuarioAction();
        return $view->renderMisPedidos(array(
                    'paginador' => array(
                        'tamanio_paginas' => ConfiguracionModel::getInstance()->getConfiguracion()->getPaginadoNumero(),
                        'pagina_actual' => (isset($_GET['pag']) ? $_GET['pag'] : 1),
                        'cant_paginas' => PedidoModelOrm::getInstance()->getMyOrdersCantPaginas($user)
                    ),
                    'pedidos' => PedidoModelOrm::getInstance()->getMyOrdersPag((isset($_GET['pag']) ? $_GET['pag'] : 1), $user),
                    "config" => ConfiguracionModel::getInstance()->getConfiguracion(),
                    'user' => $user
        ));
    }

    /**
     * 
     */
    public function viewAction() {
        $this->validateAccess(array('USUARIO ONLINE', 'ADMINISTRADOR', 'GESTION'));
        $view = new PedidoView();
        $user = SessionController::getInstance()->getUsuarioAction();
        if (!isset($_GET['id']) || $_GET['id'] == '' || !$pedido = PedidoModelOrm::getInstance()->getById((int) $_GET['id'])) {
            return $this->goNotFound();
        }
        if (SessionController::getInstance()->hasRolAction('USUARIO ONLINE') && $pedido->getRealizadoPor()->getId() != $user->getId()) {
            return $this->goAccessDeniedPage();
        }
        $fechaMax = clone $pedido->getFecha();
        $fechaMax->modify("+30 minutes");
        return $view->renderVer(array(
                    "config" => ConfiguracionModel::getInstance()->getConfiguracion(),
                    'user' => $user,
                    'esCancelable' => ($fechaMax > new DateTime('NOW') ) ? TRUE : FALSE,
                    'pedido' => $pedido
        ));
    }

    /**
     * 
     */
    public function cancelAction() {
        $this->validateAccess(array('USUARIO ONLINE', 'ADMINISTRADOR', 'GESTION'));
        $view = new PedidoView();
        $user = SessionController::getInstance()->getUsuarioAction();
        if (!isset($_GET['id']) || $_GET['id'] == '' || !$pedido = PedidoModelOrm::getInstance()->getById((int) $_GET['id'])) {
            return $this->goNotFound();
        }
        if (SessionController::getInstance()->hasRolAction('USUARIO ONLINE') && $pedido->getRealizadoPor()->getId() != $user->getId()) {
            return $this->goAccessDeniedPage();
        }
        $fechaMax = clone $pedido->getFecha();
        $fechaMax->modify("+30 minutes");
        if (SessionController::getInstance()->hasRolAction('USUARIO ONLINE') && $fechaMax < new DateTime('NOW')) {
            return $view->renderVer(array(
                        "config" => ConfiguracionModel::getInstance()->getConfiguracion(),
                        'user' => $user,
                        'error_msj' => 'Ya pasó el tiempo máximo para cancelar el pedido',
                        'esCancelable' => ($fechaMax > new DateTime('NOW') ) ? TRUE : FALSE,
                        'pedido' => $pedido
            ));
        }

        return $view->renderCancel(array(
                    "config" => ConfiguracionModel::getInstance()->getConfiguracion(),
                    'user' => $user,
                    'pedido' => $pedido
        ));
    }

    /**
     * 
     */
    public function cancel_processAction() {
        $this->validateAccess(array('USUARIO ONLINE', 'ADMINISTRADOR', 'GESTION'));
        $view = new PedidoView();
        $user = SessionController::getInstance()->getUsuarioAction();
        if (!isset($_GET['id']) || $_GET['id'] == '' || !$pedido = PedidoModelOrm::getInstance()->getById((int) $_GET['id'])) {
            return $this->goNotFound();
        }
        if (SessionController::getInstance()->hasRolAction('USUARIO ONLINE') && $pedido->getRealizadoPor()->getId() != $user->getId()) {
            return $this->goAccessDeniedPage();
        }
        $fechaMax = clone $pedido->getFecha();
        $fechaMax->modify("+30 minutes");
        if (SessionController::getInstance()->hasRolAction('USUARIO ONLINE') && $fechaMax < new DateTime('NOW')) {
            return $view->renderVer(array(
                        "config" => ConfiguracionModel::getInstance()->getConfiguracion(),
                        'user' => $user,
                        'error_msj' => 'Ya pasó el tiempo máximo para cancelar el pedido',
                        'esCancelable' => ($fechaMax > new DateTime('NOW') ) ? TRUE : FALSE,
                        'pedido' => $pedido
            ));
        }
        $estado = PedidoEstadoModelOrm::getInstance()->getByName('CANCELADO');
        $pedido->setEstado($estado);
        $pedido->setObservacion($pedido->getObservacion() . ' ' . $_POST['comentario'] . ' (' . $user->getUsuario() . ' ' . date('d/m/Y H:i:s') . ')');
        PedidoModelOrm::getInstance()->updateEntity($pedido);
        return $view->renderVer(array(
                    "config" => ConfiguracionModel::getInstance()->getConfiguracion(),
                    'user' => $user,
                    'success_msj' => 'Se canceló el pedido correctamente',
                    'pedido' => $pedido
        ));
    }

    /**
     * 
     */
    public function new_register() {
        $this->validateAccess(array('USUARIO ONLINE'));
        $param = array();
        $menuDia = PruebasBotController::getInstance()->menuDelDia();
        foreach ($_POST['producto'] as $producto) {
            if (isset($producto['checked'])) {
                $param[] = $producto;
            }
        }
        if (!$this->validateCsrf()) {//Validacion CSRF!!!
            return NULL;
        }
        if (!isset($menuDia['menu']) || count($param) == 0 || !$this->validatePedido($param, $menuDia['menu'])) {
            $view = new PedidoView();
            return $view->renderNuevo(array(
                        "config" => ConfiguracionModel::getInstance()->getConfiguracion(),
                        'user' => SessionController::getInstance()->getUsuarioAction(),
                        'error_msj' => 'Producto y/o menú incorrecto, o faltante de stock. Comuníquese con nosotros',
                        'menu' => PruebasBotController::getInstance()->menuDelDia()
            ));
        }
        $user = SessionController::getInstance()->getUsuarioAction();
        $pedido = new Pedido();
        $pedido->setEstado(PedidoEstadoModelOrm::getInstance()->getByName('PENDIENTE'));
        $comentario = $_POST['comentario'] != '' ? $_POST['comentario'] . ' (' . $user->getUsuario() . ' - ' . date('d/m/Y H:i:s') . ').- ' : '';
        $pedido->setObservacion($comentario);
        $pedido->setRealizadoPor($user);
        $id_pedido = PedidoModelOrm::getInstance()->insertEntity($pedido);
        $isError = $id_pedido ? FALSE : TRUE;
        foreach ($param as $producto) {
            if (!$isError) {
                $detalle = new PedidoDetalle();
                $detalle->setCantidad($producto['cantidad']);
                $detalle->setId_pedido($id_pedido);
                $detalle->setProducto(ProductoModel::getInstance()->getById((int) $producto['producto_id']));
                $pedido->addDetalle($detalle);
                $isError = PedidoDetalleModelOrm::getInstance()->insertEntity($detalle) ? FALSE : TRUE;
            }
        }
        $view = new PedidoView();
        if ($isError) {
            if ($id_pedido) {//RollBack
                foreach ($pedido->getDetalles() as $detalle) {
                    PedidoDetalleModelOrm::getInstance()->deleteEntity($detalle);
                }
                PedidoModelOrm::getInstance()->deleteEntity($pedido);
            }
            return $view->renderNuevo(array(
                        "config" => ConfiguracionModel::getInstance()->getConfiguracion(),
                        'user' => $user,
                        'error_msj' => 'Producto y/o menú incorrecto, o faltante de stock. Comuníquese con nosotros',
                        'menu' => PruebasBotController::getInstance()->menuDelDia()
            ));
        }

        return $view->renderMisPedidos(array(
                    'paginador' => array(
                        'tamanio_paginas' => ConfiguracionModel::getInstance()->getConfiguracion()->getPaginadoNumero(),
                        'pagina_actual' => (isset($_GET['pag']) ? $_GET['pag'] : 1),
                        'cant_paginas' => PedidoModelOrm::getInstance()->getMyOrdersCantPaginas($user)
                    ),
                    'success_msj' => 'El pedido fue realizado correctamente.',
                    'pedidos' => PedidoModelOrm::getInstance()->getMyOrdersPag((isset($_GET['pag']) ? $_GET['pag'] : 1), $user),
                    "config" => ConfiguracionModel::getInstance()->getConfiguracion(),
                    'user' => $user
        ));
    }

    /**
     * 
     */
    public function listAllAction() {
        $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
        $view = new PedidoView();
        $user = SessionController::getInstance()->getUsuarioAction();
        return $view->renderTodos(array(
                    'paginador' => array(
                        'tamanio_paginas' => ConfiguracionModel::getInstance()->getConfiguracion()->getPaginadoNumero(),
                        'pagina_actual' => (isset($_GET['pag']) ? $_GET['pag'] : 1),
                        'cant_paginas' => PedidoModelOrm::getInstance()->getAllCantPaginas()
                    ),
                    'pedidos' => PedidoModelOrm::getInstance()->getAllPag((isset($_GET['pag']) ? $_GET['pag'] : 1)),
                    "config" => ConfiguracionModel::getInstance()->getConfiguracion(),
                    'user' => $user
        ));
    }

    /**
     * 
     */
    public function listPendingAction() {
        $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
        $view = new PedidoView();
        $user = SessionController::getInstance()->getUsuarioAction();
        return $view->renderPendientes(array(
                    'paginador' => array(
                        'tamanio_paginas' => ConfiguracionModel::getInstance()->getConfiguracion()->getPaginadoNumero(),
                        'pagina_actual' => (isset($_GET['pag']) ? $_GET['pag'] : 1),
                        'cant_paginas' => PedidoModelOrm::getInstance()->getPendingCantPaginas()
                    ),
                    'pedidos' => PedidoModelOrm::getInstance()->getPendingPag((isset($_GET['pag']) ? $_GET['pag'] : 1)),
                    "config" => ConfiguracionModel::getInstance()->getConfiguracion(),
                    'user' => $user
        ));
    }

    /**
     * 
     */
    public function acceptAction() {
        $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
        $view = new PedidoView();
        $user = SessionController::getInstance()->getUsuarioAction();
        if (!isset($_POST['id']) || $_POST['id'] == '' || !$pedido = PedidoModelOrm::getInstance()->getById((int) $_POST['id'])) {
            return $this->goNotFound();
        }
        if ($pedido->getEstado()->getNombre() == 'CANCELADO' || $pedido->getEstado()->getNombre() == 'ACEPTADO') {
            return $view->renderVer(array(
                        "config" => ConfiguracionModel::getInstance()->getConfiguracion(),
                        'user' => $user,
                        'error_msj' => 'El pedido no puede ser aceptado',
                        'esCancelable' => TRUE,
                        'pedido' => $pedido
            ));
        }

        $venta = new Venta();
        $venta->setEmpleado(SessionController::getInstance()->getUsuarioAction());
        $venta->setPedido($pedido);
        $id_venta = VentaModelOrm::getInstance()->insertEntity($venta);
        $venta->setId($id_venta);
        $tipoIngreso = IngresoTipoModelOrm::getInstance()->getByNombre('VENTA ONLINE');
        $detalles = $pedido->getDetalles();
        $error = FALSE;
        foreach ($detalles as $detalle) {
            if (!$error) {
                $producto = ProductoModel::getInstance()->getById($detalle->getProducto()->getId());
                if ($producto && ($producto->getStock() - $detalle->getCantidad()) >= 0) { //Control de Stock
                    $newDetalle = new IngresoDetalle();
                    $newDetalle->setVenta($venta);
                    $newDetalle->setCantidad($detalle->getCantidad());
                    $newDetalle->setDescripcion('');
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
            return $view->renderVer(array(
                        "config" => ConfiguracionModel::getInstance()->getConfiguracion(),
                        'user' => $user,
                        'error_msj' => 'No hay stock disponible para aceptar el pedido',
                        'esCancelable' => FALSE,
                        'pedido' => $pedido
            ));
        }
        $estado = PedidoEstadoModelOrm::getInstance()->getByName('ACEPTADO');
        $pedido->setEstado($estado);
        PedidoModelOrm::getInstance()->updateEntity($pedido);
        return $view->renderVer(array(
                    "config" => ConfiguracionModel::getInstance()->getConfiguracion(),
                    'user' => $user,
                    'success_msj' => 'El pedido fue aceptado correctamente',
                    'esCancelable' => FALSE,
                    'pedido' => $pedido
        ));
    }

}
