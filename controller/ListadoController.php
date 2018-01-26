<?php
require_once (PATH_VIEW . 'ListadoView.php');
require_once (PATH_MODEL . 'ProductoModel.php');
require_once (PATH_MODEL . 'ListadoModel.php');
require_once (PATH_MODEL . 'CompraModel.php');
require_once (PATH_MODEL . 'ConfiguracionModel.php');
require_once (PATH_CONTROLLER . 'ProductoController.php');
require_once (PATH_CONTROLLER . 'ErrorHandlerController.php');
require_once (PATH_CONTROLLER . 'DefaultController.php');
require_once (PATH_CONTROLLER . 'SessionController.php');
require_once (PATH_CONTROLLER . 'Controller.php');

class ListadoController extends Controller {

    private static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self ();
        }
        return self::$instance;
    }

    public function listadoProductosFaltantes($errormsj = '') {
        $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
        $config = ConfiguracionModel::getInstance()->getConfiguracion();
        $user = SessionController::getInstance()->getUsuarioAction();

        $view = new ListadoView();
        return $view->renderProductosFanltantes(array(
                    'paginador' => array(
                        'tamanio_paginas' => ConfiguracionModel::getInstance()->getConfiguracion()->getPaginadoNumero(),
                        'pagina_actual' => (isset($_GET['pag']) ? $_GET['pag'] : 1),
                        'cant_paginas' => ListadoModel::getInstance()->getCantPaginasProductosFaltantes()
                    ),
                    'productos' => ListadoModel::getInstance()->getProductosFaltantesPag((isset($_GET['pag']) ? $_GET['pag'] : 1)),
                    "config" => ConfiguracionModel::getInstance()->getConfiguracion(),
                    'user' => SessionController::getInstance()->getUsuarioAction(),
                    'error_msj' => $errormsj
        ));
    }

    public function listadoProductosStockMinimo($errormsj = '') {
        $this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
        $config = ConfiguracionModel::getInstance()->getConfiguracion();
        $user = SessionController::getInstance()->getUsuarioAction();

        $view = new ListadoView();
        return $view->renderProductosStockMinimo(array(
                    'paginador' => array(
                        'tamanio_paginas' => ConfiguracionModel::getInstance()->getConfiguracion()->getPaginadoNumero(),
                        'pagina_actual' => (isset($_GET['pag']) ? $_GET['pag'] : 1),
                        'cant_paginas' => ListadoModel::getInstance()->getCantPaginasStockMinimo()
                    ),
                    'productos' => ListadoModel::getInstance()->getProductosStockMinimoPag((isset($_GET['pag']) ? $_GET['pag'] : 1)),
                    "config" => ConfiguracionModel::getInstance()->getConfiguracion(),
                    'user' => SessionController::getInstance()->getUsuarioAction(),
                    'error_msj' => $errormsj
        ));
    }
	public function gananciasPorDiaAction() {
		$this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
		$view = new ListadoView ();
		return $view->renderGananciasPorDia (array(
    			'user' => SessionController::getInstance()->getUsuarioAction(),
    			'config' => ConfiguracionModel::getInstance()->getConfiguracion()
    	));
	}
	public function gananciasPorDiaAjax() {
		$this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
		// Si llega desde URL entonces no se seteo nada por POST
		if(!isset($_POST['fechaDesde']))
			$this->listadoProductosFaltantes ();
		$result = $this->validateRango ( $_POST );
		if ($result['valido']) {
			$fechaInicio = date ( "Y-m-d", strtotime ( str_replace ( '/', '-', $_POST ['fechaDesde'] ) ) );
			$fechaFin = date ( "Y-m-d", strtotime ( str_replace ( '/', '-', $_POST ['fechaHasta'] ) ) );
			$index = 0;
			
			$fechas = array();
			$montos = array();
			
			for($i = $fechaInicio; $i <= $fechaFin; $i = date ( "Y-m-d", strtotime ( $i . "+ 1 days" ) )) {
				
				$fechaDate = date_format (new DateTime ( $i ), 'd/m/Y' );
				$fechas[$index]= $fechaDate;
				$monto = ListadoModel::getInstance()->getArregloDeGananciasPorDia($fechaDate);
				$montos[$index]= $monto; 
				++$index;
			}			
			$result['fechas'] = $fechas;
			$result['montos'] = $montos;
			$result['alto'] = count($fechas) < 20 ? 400 : (count($fechas) * 22);		
		}
		print json_encode($result, JSON_NUMERIC_CHECK);
	}	
	public function ventaProductosAction() {
		$this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
		$view = new ListadoView ();
		return $view->renderVentasProductosRango (array(
				'user' => SessionController::getInstance()->getUsuarioAction(),
				'config' => ConfiguracionModel::getInstance()->getConfiguracion()
		));
	}
	public function ventaProductosAjax() {
		$this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
		// Si llega desde URL entonces no se seteo nada por POST
		if(!isset($_POST['fechaDesde']))
			$this->listadoProductosFaltantes ();
		$result = $this->validateRango ( $_POST );
		if ($result['valido']) {
	 		$fechaInicio = date ( "Y-m-d", strtotime ( str_replace ( '/', '-', $_POST ['fechaDesde'] ) ) );
	 		$fechaFin = date ( "Y-m-d", strtotime ( str_replace ( '/', '-', $_POST ['fechaHasta'] ) ) );
	 		$arreglo = ListadoModel::getInstance()->getArregloDeVentaProductos($fechaInicio, $fechaFin);
	 		$result['error_msj'] = '';
	 		if(!$arreglo) {
	 			$result['valido'] = FALSE;
	 			$result['error_msj'] = "No se han efectuado ventas en el período seleccionado.";
	 		}
	 		else {
		 		$rows = array();
		 		foreach ($arreglo as $dato) {
		 			$row[0] = $dato['PRODUCTO']."  ".$dato['CANTIDAD']." vendido/s";
					$row[1] = $dato['TOTAL'];
					array_push($rows,$row);
		 		}
				$result['resultado'] = $rows;
	 		}
		}
		print json_encode($result, JSON_NUMERIC_CHECK);
	}	
	public function listadoVentaProductosAction($errormsj = '') {
		$this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
		$config = ConfiguracionModel::getInstance()->getConfiguracion();
		$user = SessionController::getInstance()->getUsuarioAction();
	
		$view = new ListadoView();
		return $view->renderListadoVentasProductos(array(
				'paginador' => array(
						'tamanio_paginas' => ConfiguracionModel::getInstance()->getConfiguracion()->getPaginadoNumero(),
						'pagina_actual' => (isset($_GET['pag']) ? $_GET['pag'] : 1),
						'cant_paginas' => ListadoModel::getInstance()->getCantPaginasProductosFaltantes()
				),
// 				'productos' => ListadoModel::getInstance()->getProductosFaltantesPag((isset($_GET['pag']) ? $_GET['pag'] : 1)),
				"config" => ConfiguracionModel::getInstance()->getConfiguracion(),
				'user' => SessionController::getInstance()->getUsuarioAction()
		));
	}
	
	public function procesarVentaProductosAction($errormsj = '') {
		$this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
 		$fechaInicio = date ( "Y-m-d", strtotime ( str_replace ( '/', '-', $_POST ['fechaDesde'] ) ) );
 		$fechaFin = date ( "Y-m-d", strtotime ( str_replace ( '/', '-', $_POST ['fechaHasta'] ) ) );
		$productos = ListadoModel::getInstance()->getArregloDeVentaProductosPag($fechaInicio, $fechaFin, (isset($_POST['pag']) ? $_POST['pag'] : 1));
		$validar = $this->validateRango($_POST);
		if (!$validar['valido']){
			$data['error'] = true;
			$data['msj'] = $validar['error_msj'];
			echo json_encode ($data);
			die();				
		}
		if (!$productos){
			$data['error'] = true;
			$data['msj'] = 'No hay resultados para el rango '.$fechaInicio.' - '.$fechaFin;
		}else{
			$paginador['tamanio_paginas'] = ConfiguracionModel::getInstance()->getConfiguracion()->getPaginadoNumero();
			$paginador['pagina_actual'] = (isset($_POST['pag']) ? $_POST['pag'] : 1);
			$paginador['cant_paginas'] = ListadoModel::getInstance()->getCantPaginasVentaProductos($fechaInicio, $fechaFin);
			
			$data['error'] = false;
			$data['msj'] = 'Resultados para el rango '.$fechaInicio.' - '.$fechaFin;
			$view = new ListadoView();
			$data['productos'] = $view->renderListadoRangoVentasProductos(array('paginador' => $paginador, 'productos' => $productos));
			$data['paginador'] = $view->renderPaginadorRangoVentasProductos($paginador);
				
		}
		//header('Content-Type: application/json');
		echo json_encode ($data);
	}
	
	//HACER PARA GANACIA POR DIA render
	public function listadoGananciaPorDiaAction($errormsj = '') {
		$this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
		$config = ConfiguracionModel::getInstance()->getConfiguracion();
		$user = SessionController::getInstance()->getUsuarioAction();
	
		$view = new ListadoView();
		return $view->renderListadoGananciaPorDia(array(
				'paginador' => array(
						'tamanio_paginas' => ConfiguracionModel::getInstance()->getConfiguracion()->getPaginadoNumero(),
						'pagina_actual' => (isset($_GET['pag']) ? $_GET['pag'] : 1),
						'cant_paginas' => ListadoModel::getInstance()->getCantPaginasProductosFaltantes()
				),
				// 'productos' => ListadoModel::getInstance()->getProductosFaltantesPag((isset($_GET['pag']) ? $_GET['pag'] : 1)),
				"config" => ConfiguracionModel::getInstance()->getConfiguracion(),
				'user' => SessionController::getInstance()->getUsuarioAction()
		));
	}
	
	public function procesarGananciaPorDiaAction($errormsj = '') {
		$this->validateAccess(array('ADMINISTRADOR', 'GESTION'));
		$result = $this->validateRango ( $_POST );
		if ($result['valido']) {
			$tamanioPag = ConfiguracionModel::getInstance ()->getConfiguracion ()->getPaginadoNumero ();
			$pagina = (isset ( $_POST ['pag'] ) ? $_POST ['pag'] : 1);
			$fechaInicio = date ( "Y-m-d", strtotime ( str_replace ( '/', '-', $_POST ['fechaDesde'] ) ) );
			$fechaFin = date ( "Y-m-d", strtotime ( str_replace ( '/', '-', $_POST ['fechaHasta'] ) ) );
			$paginador ['tamanio_paginas'] = $tamanioPag;
			$paginador ['pagina_actual'] = $pagina;
						
			// en datos se guarda todo.
			$datos = array();	
			$index = 0;
			for($i = $fechaInicio; $i <= $fechaFin; $i = date ( "Y-m-d", strtotime ( $i . "+ 1 days" ) )) {
		
				$fechaDate = date_format (new DateTime ( $i ), 'd/m/Y' );
				$dato['fecha'] = $fechaDate;
				$dato['monto'] = ListadoModel::getInstance()->getArregloDeGananciasPorDia($fechaDate); 
				$datos[$index]= $dato;
				++$index;
			}
			
			$cantidadPaginas = ceil(count($datos) / $tamanioPag);
			$paginador ['cant_paginas'] = $cantidadPaginas;
			$totalFilas = count($datos);	
			// hay que emular la obtencion de la pagina
			$datosMostrar = array();
			$inicio = ($pagina - 1) * $tamanioPag;
			for ($j = 0; $j <= $tamanioPag - 1; $j++) {
				if (($inicio + $j + 1) <= $totalFilas)
					$datosMostrar[$j]= $datos[$inicio + $j];
				else break;
			}
		}
		
		if (count($datosMostrar) < 1) {
			$data ['error'] = true;
			$data ['msj'] = 'No hay resultados de ganancias para el rango ' . $fechaInicio . ' - ' . $fechaFin;
		} else {	
			$data ['error'] = false;
			$data ['msj'] = 'Resultados de ganancias para el rango ' . $fechaInicio . ' - ' . $fechaFin;
			$view = new ListadoView ();
			$data ['productos'] = $view->renderListadoRangoGananciaPorDia( array (
					'paginador' => $paginador,
					'resultados' => $datosMostrar 
			) );
			$data ['paginador'] = $view->renderPaginadorRangoGananciaPorDia( $paginador );
		}
		echo json_encode ( $data );
	}
	
    private function validateRango($arreglo) {
    	$result['valido'] = FALSE;
    	if (!isset($arreglo['fechaDesde']) || !$this->isDate($arreglo['fechaDesde'])) {
    		$result['error_msj'] = 'Debe seleccionar un rango de fechas válido con formato "dd/mm/aaaa" y no puede estar ningún campo vacío.';
    		return $result;
    	}
    	if (!isset($arreglo['fechaHasta']) || !$this->isDate($arreglo['fechaHasta'])) {
    		$result['error_msj'] = 'Debe seleccionar un rango de fechas válido con formato "dd/mm/aaaa" y no puede estar ningún campo vacío.';
    		return $result;
    	}
    	$fechaInicio = date ( "Y-m-d", strtotime ( str_replace ( '/', '-', $arreglo['fechaDesde'] ) ) );
    	$fechaFin = date ( "Y-m-d", strtotime ( str_replace ( '/', '-', $arreglo['fechaHasta'] ) ) );   
    	if ($fechaInicio > $fechaFin) {
    		$result['error_msj'] = 'La fecha desde del rango de fechas no puede ser mayor a la fecha hasta.';
    		return $result;
    	}
    	date_default_timezone_set('America/Argentina/Buenos_Aires');
    	$fechaActual = date("Y-m-d");    	 
    	if ($fechaFin > $fechaActual) {
    		$result['error_msj'] = 'La fecha hasta del rango de fechas no puede ser mayor a la fecha del día.';
    		return $result;
    	}
    	$result['valido'] = TRUE;
    	return $result;
    }
}
