<?php

/*
  ini_set ( 'display_startup_errors', 1 );
  ini_set ( 'display_errors', 1 );
  error_reporting ( - 1 );
 */
require_once ('./config/config.php');
require_once (PATH_CONTROLLER . 'CompraController.php');
require_once (PATH_CONTROLLER . 'ErrorHandlerController.php');

// Se chequea si el requerimiento http es via GET
if (!isset($_GET["action"])) {
    CompraController::getInstance()->listadoComprasAction();
} else {
    switch ($_GET ["action"]) {
        case 'descargar_factura' :
            CompraController::getInstance()->descargarFactura();
            break;
        case 'nueva_compra' :
            CompraController::getInstance()->nuevaCompraAction();
            break;
        case 'submit_compra' :
            CompraController::getInstance()->nuevaCompraSubmitAction();
            break;
        case 'ver_compra' :
            CompraController::getInstance()->verCompraAction();
            break;
        case 'editar_compra' :
            CompraController::getInstance()->editarCompraAction();
            break;
        case 'submit_editar_compra' :
            CompraController::getInstance()->editarCompraSubmitAction();
            break;
        case 'eliminar_compra' :
            CompraController::getInstance()->eliminarCompraAction();
            break;
        case 'listado_compras' :
            CompraController::getInstance()->listadoComprasAction();
            break;
        case 'finalizar' :
            CompraController::getInstance()->finalizarCompra();
            break;
        case 'busqueda':
            CompraController::getInstance()->searchAction();
            break;
        case 'nuevo_egreso_detalle' :
            CompraController::getInstance()->nuevoEgresoDetalle();
            break;
        case 'submit_detalle_egreso' :
            CompraController::getInstance()->nuevaDetalleEgresoSubmitAction();
            break;
        case 'editar_compra_detalle_egreso' :
            CompraController::getInstance()->editarCompraDetalleEgresoAction();
            break;
        case 'eliminar_egreso_detalle' :
            CompraController::getInstance()->eliminarEgresoDetalle();
            break;
        default :
            CompraController::getInstance()->listadoComprasAction();
            break;
    }
}
