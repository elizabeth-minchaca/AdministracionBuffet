<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(- 1);

require_once('./config/config.php');
require_once(PATH_CONTROLLER . 'PedidoController.php');

if (!isset($_GET["action"])) {
    PedidoController::getInstance()->newAction();
} else {
    switch ($_GET["action"]) {
        case 'nuevo':
            PedidoController::getInstance()->newAction();
            break;
        case 'mis_pedidos':
            PedidoController::getInstance()->myOrdersAction();
            break;
        case 'ver':
            PedidoController::getInstance()->viewAction();
            break;
        case 'cancelar':
            PedidoController::getInstance()->cancelAction();
            break;
        case 'cancelar_accion':
            PedidoController::getInstance()->cancel_processAction();
            break;
        case 'listado_todos':
            PedidoController::getInstance()->listAllAction();
            break;
        case 'listado_pendientes':
            PedidoController::getInstance()->listPendingAction();
            break;
        case 'aceptar':
            PedidoController::getInstance()->acceptAction();
            break;
        case 'registrar':
            PedidoController::getInstance()->new_register();
            break;
        default:
            PedidoController::getInstance()->myOrdersAction();
            break;
    }
}