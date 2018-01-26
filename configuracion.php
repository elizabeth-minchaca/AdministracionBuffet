<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

require_once('./config/config.php');
require_once(PATH_CONTROLLER . 'ConfiguracionController.php');
require_once(PATH_CONTROLLER . 'ErrorHandlerController.php');

// Se chequea si el requerimiento http es via GET
if (!isset($_GET["action"])) {
    ConfiguracionController::getInstance()->mostrarConfiguracion();
} else {
    switch ($_GET["action"]) {
        case 'mostrar':
            ConfiguracionController::getInstance()->mostrarConfiguracion();
            break;
        case 'editar':
            ConfiguracionController::getInstance()->editSubmitAction();
            break;
        case 'mostrar_form':
            ConfiguracionController::getInstance()->editarConfiguracion();
            break;
        default:
            ConfiguracionController::getInstance()->mostrarConfiguracion();
            break;
    }
}
