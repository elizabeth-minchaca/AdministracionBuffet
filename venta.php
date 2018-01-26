<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(- 1);

require_once('./config/config.php');
require_once(PATH_CONTROLLER . 'VentaController.php');

if (!isset($_GET["action"])) {
    VentaController::getInstance()->newAction();
} else {
    switch ($_GET["action"]) {
        case 'ver':
            VentaController::getInstance()->viewAction();
            break;
        case 'listado':
            VentaController::getInstance()->listAction();
            break;
        case 'nueva':
            VentaController::getInstance()->newAction();
            break;
        case 'registrar':
            VentaController::getInstance()->new_proccessAction();
            break;
        case 'busqueda':
            VentaController::getInstance()->searchAction();
            break;
        case 'cancelar':
            VentaController::getInstance()->cancelAction();
            break;
        default:
            VentaController::getInstance()->newAction();
            break;
    }
}