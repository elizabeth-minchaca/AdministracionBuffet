<?php
/*
ini_set ( 'display_startup_errors', 1 );
ini_set ( 'display_errors', 1 );
error_reporting ( - 1 );
*/
require_once ('./config/config.php');
require_once (PATH_CONTROLLER . 'MenuController.php');
require_once (PATH_CONTROLLER . 'ErrorHandlerController.php');

// Se chequea si el requerimiento http es via GET
if (!isset($_GET["action"])) {
	MenuController::getInstance()->listadoMenuAction();
} else {
	switch ($_GET ["action"]) {
		case 'nuevo_menu' :
			MenuController::getInstance ()->nuevoMenuAction ();
			break;
		case 'submit_menu' :
			MenuController::getInstance ()->nuevoMenuSubmitAction ();
			break;	
		case 'ver_menu' :
			MenuController::getInstance ()->verMenuAction ();
			break;
		case 'editar_menu' :
			MenuController::getInstance ()->editarMenuAction ();
			break;	
		case 'submit_editar_menu' :
			MenuController::getInstance ()->editarMenuSubmitAction ();
			break;
		case 'listado_menu' :
			MenuController::getInstance ()->listadoMenuAction ();
			break;
		case 'editar_menu_detalle' :
			MenuController::getInstance ()->editarMenuDetalleAction();
			break;	
		case 'eliminar_menu' :
			MenuController::getInstance ()->eliminarMenuAction ();
			break;
		case 'habilitar' :
			MenuController::getInstance ()->habilitarMenu ();
			break;							
		case 'busqueda':
			 MenuController::getInstance()->searchAction();
			 break;
		case 'nuevo_detalle' :
			 MenuController::getInstance ()->nuevoDetalleAction ();
			 break;
		case 'eliminar_detalle' :
			 MenuController::getInstance ()->eliminarDetalle ();
			 break;			 
		case 'submit_detalle' :
			MenuController::getInstance ()->nuevoDetalleSubmitAction ();
			break;
		default :
			MenuController::getInstance ()->listadoMenuAction ();
			break;
	}
	
}
