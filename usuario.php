<?php
/*
  ini_set('display_startup_errors', 1);
  ini_set('display_errors', 1);
  error_reporting(- 1);
 */
require_once('./config/config.php');
require_once(PATH_CONTROLLER . 'UsuarioController.php');

if (!isset($_GET["action"])) {
    UsuarioController::getInstance()->listAction();
} else {
    switch ($_GET["action"]) {
        case 'registrar':
            UsuarioController::getInstance()->new_processAction();
            break;
        case 'listado':
            UsuarioController::getInstance()->listAction();
            break;
        case 'nuevo':
            UsuarioController::getInstance()->newAction();
            break;
        case 'editar':
            UsuarioController::getInstance()->editAction();
            break;
        case 'modificar':
            UsuarioController::getInstance()->edit_processAction();
            break;
        case 'ver':
            UsuarioController::getInstance()->showAction();
            break;
        case 'eliminar':
            UsuarioController::getInstance()->deleteAction();
            break;
        case 'uonline_habilitar':
            UsuarioController::getInstance()->listOnlineAction();
            break;
        default:
            UsuarioController::getInstance()->listAction();
            break;
    }
}