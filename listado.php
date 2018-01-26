<?php
/*
ini_set ( 'display_startup_errors', 1 );
ini_set ( 'display_errors', 1 );
error_reporting ( - 1 );
*/
require_once ('./config/config.php');
require_once (PATH_CONTROLLER . 'ListadoController.php');
require_once (PATH_CONTROLLER . 'ProductoController.php');
require_once (PATH_CONTROLLER . 'ErrorHandlerController.php');

// Se chequea si el requerimiento http es via GET
if (!isset($_GET["action"])) {
		ListadoController::getInstance ()->listadoProductosFaltantes();
} else {
	switch ($_GET ["action"]) {
		case 'ver_producto_faltante' :
			ListadoController::getInstance ()->verActionProductoFaltante ();
			break;
		case 'ver_producto_stock_min' :
			ListadoController::getInstance ()->verActionProductoStockMinimo ();
			break;
		case 'productos_faltantes' :
			ListadoController::getInstance ()->listadoProductosFaltantes ();
			break;
		case 'ganancias_dia' :
			ListadoController::getInstance ()->gananciasPorDiaAction ();
			break;
		case 'grafico_ganancias' :
			ListadoController::getInstance ()->gananciasPorDiaAjax ();
			break;
		case 'venta_productos' :
			ListadoController::getInstance ()->ventaProductosAction ();
			break;
		case 'grafico_ventas' :
			ListadoController::getInstance ()->ventaProductosAjax ();
			break;
		case 'productos_stock_min' :
			ListadoController::getInstance ()->listadoProductosStockMinimo ();
			break;
		case 'listado_ventas' :
			ListadoController::getInstance ()->listadoVentaProductosAction ();
			break;
		case 'buscar_ventas' :
			 ListadoController::getInstance ()->procesarVentaProductosAction ();
			break;
		case 'listado_ganancias' :
			ListadoController::getInstance ()->listadoGananciaPorDiaAction ();
			break;
		case 'buscar_ganancias' :
			ListadoController::getInstance ()->procesarGananciaPorDiaAction ();
			break;

		default :
			ListadoController::getInstance ()->listadoProductosFaltantes ();
			break;
	}
}
