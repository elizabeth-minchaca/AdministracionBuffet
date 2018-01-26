<?php

require_once (PATH_MODEL . 'PDORepository.php');
require_once (PATH_CLASS . 'Venta.php');
require_once (PATH_MODEL . 'PedidoModelOrm.php');
require_once (PATH_MODEL . 'DetalleIngresoModelOrm.php');

class VentaModelOrm extends PDORepository {
	
	const  TABLA = "VENTA";
    
	private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Convierte un la Entidad de Arreglo a Clase
     */
    private function getEntityByArray($arrayEntity) {
        $entity = new Venta();
        $entity->setBajaLogica($arrayEntity['bajaLogica']);
        $entity->setEmpleado(UsuarioModelOrm::getInstance()->getById($arrayEntity['id_empleado']));
        $entity->setFecha(DateTime::createFromFormat('Y-m-d H:i:s', $arrayEntity['fecha']));
        $entity->setId($arrayEntity['id']);
        if ($arrayEntity['id_pedido']) {
            $entity->setPedido(PedidoModelOrm::getInstance()->getById($arrayEntity['id_pedido']));
        }
        $detalles = DetalleIngresoModelOrm::getInstance()->getByIdVenta($arrayEntity['id']);
        $entity->setDetalles($detalles);
        return $entity;
    }

    /**
     * 
     */
    public function insertEntity(Venta $venta) {
        return $this->insert("VENTA", array(
                    'id_empleado' => $venta->getEmpleado()->getId(),
                    'id_pedido' => (($venta->getPedido()) ? $venta->getPedido()->getId() : NULL )
        ));
    }

    /**
     * 
     */
    public function deleteEntity(Venta $venta) {
        return $this->delete("VENTA", $venta->getId());
    }

    /**
     * 
     */
    public function cancelVenta(Venta $venta) {
        $data = array('bajaLogica' => 1);
        return $this->update("VENTA", $data, $venta->getId());
    }

    /**
     * 
     */
    public function getById($id) {
        $consulta = ""
                . "SELECT * "
                . "FROM VENTA "
                . "WHERE id = :id ";

        $result = $this->select($consulta, array(
            'id' => $id
        ));
        if (count($result) > 0) {
            $elemento = reset($result);
            return $this->getEntityByArray($elemento);
        } else {
            return FALSE;
        }
    }

    /**
     * 
     */
    public function getVentasPag($nroPagina) {
        $where = '';
        $result = $this->getGenericoTablaPaginado('VENTA', $nroPagina, $where, 'fecha', 'DESC');
        $arreglo = array();
        foreach ($result as $entity) {
            $arreglo[] = $this->getEntityByArray($entity);
        }
        return $arreglo;
    }

    public function getCantPaginas() {
    	$where = ' ';
        return $this->getGenericoCantPaginas('VENTA',$where);
    }

    public function getMontoIngresosPorFecha($fecha) {
    
    	$consulta = "SELECT IFNULL(sum(detalle.precio_unitario * detalle.cantidad),0) AS MONTO FROM " . self::TABLA . " venta INNER JOIN " . DetalleIngresoModelOrm::TABLA . " detalle ON venta.id = detalle.id_venta WHERE venta.bajaLogica = 0 AND DATE_FORMAT(venta.fecha,'%d/%m/%Y') = :fecha AND detalle.bajaLogica = 0  ";
    	$result = $this->select ( $consulta, array (
    			'fecha' => $fecha
    	) );
    	return $result[0]['MONTO'];
    }
    
    public function getCantidadProductosVendidos($fechaDesde, $fechaHasta) {
    	$consulta = "SELECT IFNULL(sum(detalle.cantidad),0) AS TOTAL FROM " . self::TABLA . " venta INNER JOIN " . DetalleIngresoModelOrm::TABLA . " detalle ON venta.id = detalle.id_venta WHERE venta.bajaLogica = 0 AND DATE_FORMAT(venta.fecha,'%Y-%m-%d') >= :fechaD AND DATE_FORMAT(venta.fecha,'%Y-%m-%d') <= :fechaH AND detalle.bajaLogica = 0  ";
    	$result = $this->select ( $consulta, array (
    			'fechaD' => $fechaDesde,
    			'fechaH' => $fechaHasta,
    	) );
    	return $result[0]['TOTAL'];
    }
}
