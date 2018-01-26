<?php
/*
ini_set ( 'display_startup_errors', 1 );
ini_set ( 'display_errors', 1 );
error_reporting ( - 1 );
*/
require_once ('./config/config.php');
require_once (PATH_CONTROLLER . 'ProductoController.php');
require_once (PATH_CONTROLLER . 'ErrorHandlerController.php');
	
// Se chequea si el requerimiento http es via GET
if (!isset($_GET["action"])) {
		ProductoController::getInstance()->listadoAction();
} else {
	switch ($_GET ["action"]) {
		case 'nuevo' :
			ProductoController::getInstance ()->nuevoAction ();
			break;
		case 'editar_producto' :
			ProductoController::getInstance ()->editarAction ();
			break;
		case 'ver_producto' :
			ProductoController::getInstance ()->verAction ();
			break;
		case 'listado_productos' :
			ProductoController::getInstance ()->listadoAction ();
			break;
		case 'eliminar_producto' :
			ProductoController::getInstance ()->eliminarAction ();
			break;
		case 'submit_producto' :
			ProductoController::getInstance ()->nuevoProductoSubmitAction ();
			break;
		case 'submit_editar_producto' :
			ProductoController::getInstance ()->editarProductoSubmitAction ();
			break;			
		default :
			ProductoController::getInstance ()->listadoAction ();
			break;
	}
	
}