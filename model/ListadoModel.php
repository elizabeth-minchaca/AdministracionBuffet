<?php
require_once (PATH_MODEL . 'PDORepository.php');
require_once (PATH_CLASS . 'Producto.php');
require_once (PATH_MODEL . 'CategoriaModel.php');
require_once (PATH_MODEL . 'ProductoModel.php');
require_once (PATH_MODEL . 'VentaModelOrm.php');


class ListadoModel extends PDORepository {
	const TABLA = "PRODUCTO";
	
	private static $instance;
	
	public static function getInstance() {
	
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
		
	//USANDO PAGINADO GENERICO
	public function getProductosFaltantesPag($nroPagina) {
		$where = ' WHERE bajaLogica = 0 AND stock =0 ';
		$result = $this->getGenericoTablaPaginado(self::TABLA, $nroPagina, $where, 'nombre');
        return ProductoModel::getInstance()->arregloDeProductos($result);
	}
	
	public function getCantPaginasProductosFaltantes() {
		$where = ' WHERE bajaLogica = 0 AND stock = 0 ';
		return $this->getGenericoCantPaginas(self::TABLA, $where);
	}
	
	public function getProductosStockMinimoPag($nroPagina) {
		$where = ' WHERE bajaLogica = 0 AND stock >= 0 AND stock < stock_minimo ';
		$result = $this->getGenericoTablaPaginado(self::TABLA, $nroPagina, $where, 'nombre');
		return ProductoModel::getInstance()->arregloDeProductos($result);
	}
	
	public function getCantPaginasStockMinimo() {
		$where = ' WHERE bajaLogica = 0 AND stock >= 0 AND stock < stock_minimo ';
		return $this->getGenericoCantPaginas(self::TABLA, $where);
	}
	/*
	 * Para una fecha en formato dd/mm/aaaa calcula la ganancia(ingresos - egresos) por dia
	 */
	public function getArregloDeGananciasPorDia($fecha) {
		$ingresosDia = VentaModelOrm::getInstance()->getMontoIngresosPorFecha($fecha);
		$egresosDia = CompraModel::getInstance()->getMontoEgresosPorFecha($fecha);
		return $ingresosDia - $egresosDia;
	}
	/*
	 * Para un rango de fechas en formato dd/mm/aaaa calcula el porcentaje de cada producto vendido para el total de ese rango.
	 */
	public function getArregloDeVentaProductos($fechaDesde, $fechaHasta) {
		$total=VentaModelOrm::getInstance()->getCantidadProductosVendidos($fechaDesde, $fechaHasta);
		$consulta = "SELECT producto.nombre AS PRODUCTO, sum(detalle.cantidad) AS CANTIDAD, (SUM(detalle.cantidad)*100/ :total) AS TOTAL FROM VENTA venta INNER JOIN DETALLE_INGRESO detalle ON venta.id = detalle.id_venta INNER JOIN PRODUCTO producto ON detalle.id_producto = producto.id WHERE venta.bajaLogica = 0 AND DATE_FORMAT(venta.fecha,'%Y-%m-%d') >= :fechaD AND DATE_FORMAT(venta.fecha,'%Y-%m-%d') <= :fechaH AND detalle.bajaLogica = 0 GROUP BY detalle.id_producto ";
		$result = $this->select ( $consulta, array (
				'total' => $total,
				'fechaD' => $fechaDesde,
				'fechaH' => $fechaHasta,
		) );
		return (count($result) > 0) ? $result : FALSE;
	}
	/*
	 * Para un rango de fechas en formato dd/mm/aaaa calcula el porcentaje de cada producto vendido para el total de ese rango.
	 */
	public function getArregloDeVentaProductosPag($fechaDesde, $fechaHasta, $pagina) {
		$tamanioPag = ConfiguracionModel::getInstance()->getConfiguracion()->getPaginadoNumero();
		$inicio = ($pagina - 1) * $tamanioPag;
		$total = VentaModelOrm::getInstance ()->getCantidadProductosVendidos ( $fechaDesde, $fechaHasta );
		$sql = "SELECT "
				."producto.nombre AS PRODUCTO, detalle.cantidad AS CANTIDAD, (SUM(detalle.cantidad)*100/ :total) AS TOTAL "
				."FROM VENTA venta INNER JOIN DETALLE_INGRESO detalle ON venta.id = detalle.id_venta INNER JOIN PRODUCTO producto "
				."ON detalle.id_producto = producto.id "
				."WHERE venta.bajaLogica = 0 AND DATE_FORMAT(venta.fecha,'%Y-%m-%d') >= :fechaD "
				."AND DATE_FORMAT(venta.fecha,'%Y-%m-%d') <= :fechaH AND detalle.bajaLogica = 0 "
				."GROUP BY detalle.id_producto "
				."ORDER BY producto.nombre "
				."LIMIT :inicio, :tamanio ";
		$result = $this->select ( $sql, array (
				'total' => $total,
				'fechaD' => $fechaDesde,
				'fechaH' => $fechaHasta,
				'inicio' => $inicio,
				'tamanio' => $tamanioPag
		) );
		return (count ( $result ) > 0) ? $result : FALSE;
	}
	public function getCantPaginasVentaProductos($fechaDesde, $fechaHasta) {
		$tamanioPag = ConfiguracionModel::getInstance()->getConfiguracion()->getPaginadoNumero();
		$sql = "SELECT "
				."producto.nombre AS PRODUCTO, detalle.cantidad AS CANTIDAD "
				."FROM VENTA venta INNER JOIN DETALLE_INGRESO detalle ON venta.id = detalle.id_venta INNER JOIN PRODUCTO producto "
				."ON detalle.id_producto = producto.id "
				."WHERE venta.bajaLogica = 0 AND DATE_FORMAT(venta.fecha,'%Y-%m-%d') >= :fechaD "
				."AND DATE_FORMAT(venta.fecha,'%Y-%m-%d') <= :fechaH AND detalle.bajaLogica = 0 "
				."GROUP BY detalle.id_producto ";
		$result = $this->select ( $sql, array (
				'fechaD' => $fechaDesde,
				'fechaH' => $fechaHasta
		) );
		return ceil(count($result) / $tamanioPag);
	}
}
