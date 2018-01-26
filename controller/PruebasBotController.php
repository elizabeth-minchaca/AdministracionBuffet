<?php

require_once (PATH_MODEL . 'MenuModel.php');
require_once (PATH_MODEL . 'DetalleMenuModel.php');
require_once (PATH_MODEL . 'ProductoModel.php');
require_once (PATH_MODEL . 'ConfiguracionModel.php');
require_once (PATH_VIEW . 'PruebasBotView.php');
require_once (PATH_MODEL . 'PruebasBotModel.php');

//require_once (PATH_CONTROLLER . 'ErrorHandlerController.php');
//require_once (PATH_CONTROLLER . 'Controller.php');
require_once(PATH_CONTROLLER . 'SessionController.php');

//require_once (PATH_CONTROLLER . 'DefaultController.php');

class PruebasBotController {

    private static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self ();
        }
        return self::$instance;
    }

    public  function menuDelDia(){
    	date_default_timezone_set('America/Argentina/Buenos_Aires');
    	$fecha = date("d-m-Y");
    	$arreglo = array();
    	if (($menu = MenuModel::getInstance()->existeFecha(date("Y-m-d")))/* && ($menu->getHabilitada()) */) {
    		if (!$menu->getHabilitada()) {
    			if (count($menu->getMenuDetalles()) < 1) {
    				$arreglo['msj'] = "No hay un menu disponible para hoy $fecha";  
    				return $arreglo;
    			}else {
    				MenuModel::getInstance()->habilitar($menu->getId());    				
    			}
    		}
    		$arreglo['menu'] = $menu;
    	} else {
    		$arreglo['msj'] = "Lo sentimos, no hay un menu disponible para hoy $fecha";
    	}
    	return $arreglo;
    	 
    }
    
    private function hayMenuDelDia(){
    	if ($menu = MenuModel::getInstance()->existeFecha(date("Y-m-d"))/* && ($menu->getHabilitada()) */) {
    		if (!$menu->getHabilitada()) {
    			if (count($menu->getMenuDetalles()) < 1) {
    				return false;
    			}else {
					return true;
    			}
    		}
    		return true;
    	}else{
    		return false;
    	}
    		 
    }
    
    public function menuDelDiaBot() {
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fecha = date("d-m-Y");
        $arreglo['menu'] = NULL;
        if ($menu = MenuModel::getInstance()->existeFecha(date("Y-m-d"))/* && ($menu->getHabilitada()) */) {
            if (!$menu->getHabilitada()) {
                if (count($menu->getMenuDetalles()) < 1) {
    				$arreglo['menu'] = "Lo sentimos, no hay un menu disponible para hoy $fecha";  
    				return $arreglo;
    			}else {
    				MenuModel::getInstance()->habilitar($menu->getId());    				
    			}
            }
            $arreglo['menu'] = "Menú del día " . PHP_EOL;
            foreach ($menu->getMenuDetalles() as $detalle) {
                $cantidad = $detalle->getCantidad();
                $nameProd = $detalle->getProducto()->getNombre();
                $arreglo['menu'] .= "" . $cantidad . " - " . $nameProd . " " . PHP_EOL;
            }
            $precio = $menu->getPrecioMenu();
            $arreglo['menu'] .= "Precio: $" . $precio;
        } else {
            $arreglo['menu'] = "Lo sentimos, no hay un menú disponible para hoy $fecha";
        }
        return $arreglo;
    }

    public function menuParaMañana() {
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fecha = date("d-m-Y");
        $mañana2 = date('d-m-Y', strtotime("$fecha + 1 day"));

        $arreglo['menu'] = NULL;
        $hoy = date("Y-m-d");
        $mañana = date('Y-m-d', strtotime("$hoy + 1 day"));
        if ($menu = MenuModel::getInstance()->existeFecha($mañana)/* && ($menu->getHabilitada()) */) {
            $arreglo['menu'] = "Menú para mañana " . PHP_EOL;
            foreach ($menu->getMenuDetalles() as $detalle) {
                $cantidad = $detalle->getCantidad();
                $nameProd = $detalle->getProducto()->getNombre();
                $arreglo['menu'] .= "" . $cantidad . " - " . $nameProd . " " . PHP_EOL;
            }
            $precio = $menu->getPrecioMenu();
            $arreglo['menu'] .= "Precio: $" . $precio;
        } else {
            $arreglo['menu'] = "Lo sentimos, no hay un menú disponible para mañana $mañana2";
        }
        return $arreglo;
    }

    public function suscribirUsuario($response) {

        $fromid = $response['message']['from']['id'];
        $firstname = $response['message']['from']['first_name'];
        $username = $response['message']['from']['username'];
        if (!$user = PruebasBotModel::getInstance()->existeUsuario($username, $firstname, $fromid)) {
            $data = array(
                'fromid' => $fromid,
                'firstname' => $firstname,
                'username' => $username
            );
            PruebasBotModel::getInstance()->nuevo($data);
        }
        return $user;
    }

    public function enviarMenuDelDia() {
        if(!$this->hayMenuDelDia()){
        	header('Content-Type: application/json');
        	echo json_encode(array(
        			"error" => true,
        			"msj" => 'Lo sentimos, no hay men&uacute; del día para enviar.'
        	));
        	die();
        }
        
        $users = PruebasBotModel::getInstance()->getAllUsuariosBot();
        if (!$users) {
            header('Content-Type: application/json');
            echo json_encode(array(
                "error" => true,
                "msj" => 'No hay usuarios Telegram para enviar el men&uacute; del dia'
            ));
            die();
        } else {
            $url = 'https://api.telegram.org/bot272365236:AAG2OybNTosUc0BFU19DBUtc1rMyZg_UaOI/sendMessage';
            foreach ($users as $user) {
                $msg = array();
                $msg['chat_id'] = $user->getFromId();
                $msg['text'] = null;
                $msg['disable_web_page_preview'] = true;
                $msg['reply_to_message_id'] = null; /* $response['message']['message_id'] */
                $msg['reply_markup'] = null;
                $menu = $this->menuDelDiaBot();
				$msg ['text'] = 'Hola ' . $user->getFirstName() . PHP_EOL;
                $msg['text'] .= $menu['menu'];
                $options = array(
                    'http' => array(
                        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                        'method' => 'POST',
                        'content' => http_build_query($msg)
                    )
                );

                $context = stream_context_create($options);
                $result = file_get_contents($url, false, $context);

                //exit(0);
            }
            header('Content-Type: application/json');
            echo json_encode(array(
                "error" => false,
                "msj" => 'El men&uacute; del dia fue enviado a los usuarios Telegram.'
            ));
        }
    }

    public function listarUsuariosBot($error_msj = '', $success_msj = '') {
        $view = new PruebasBotView();
        return $view->renderListadoUsuariosBot(array(
                    'paginador' => array(
                        'tamanio_paginas' => ConfiguracionModel::getInstance()->getConfiguracion()->getPaginadoNumero(),
                        'pagina_actual' => (isset($_GET['pag']) ? $_GET['pag'] : 1),
                        'cant_paginas' => PruebasBotModel::getInstance()->getCantPaginas()
                    ),
                    'usuarios' => PruebasBotModel::getInstance()->getUsuarioBotPag((isset($_GET['pag']) ? $_GET['pag'] : 1)),
                    "config" => ConfiguracionModel::getInstance()->getConfiguracion(),
                    'user' => SessionController::getInstance()->getUsuarioAction(),
                    'error_msj' => $error_msj,
                    'success_msj' => $success_msj
        ));
    }

}
