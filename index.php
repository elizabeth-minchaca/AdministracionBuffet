<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(- 1);


require_once('./config/config.php');
require_once(PATH_CONTROLLER . 'DefaultController.php');
require_once(PATH_CONTROLLER . 'ErrorHandlerController.php');

if (!isset($_GET["action"])) {
    DefaultController::getInstance()->indexAction();
} else {
    switch ($_GET["action"]) {
        case 'registrar_process':
            DefaultController::getInstance()->registerProcessAction();
            break;
        case 'registrar':
            DefaultController::getInstance()->registerAction();
            break;
        case 'login':
            DefaultController::getInstance()->loginAction();
            break;
        case 'home':
            DefaultController::getInstance()->homeAction();
            break;
        case 'logout':
            DefaultController::getInstance()->logoutAction();
            break;
        case 'login_process':
            DefaultController::getInstance()->accessAction();
            break;
        default:
            DefaultController::getInstance()->indexAction();
            break;
    }
}
