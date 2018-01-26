<?php

require_once (PATH_MODEL . 'PDORepository.php');
require_once (PATH_CLASS . 'IngresoDetalle.php');
require_once (PATH_MODEL . 'ProductoModel.php');
require_once (PATH_MODEL . 'IngresoTipoModelOrm.php');
require_once (PATH_MODEL . 'VentaModelOrm.php');

class DetalleIngresoModelOrm extends PDORepository {

	const TABLA = "DETALLE_INGRESO";
	
    private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * 
     */
    public function insertEntity(IngresoDetalle $entity) {
        return $this->insert("DETALLE_INGRESO", array(
                    'cantidad' => $entity->getCantidad(),
                    'id_venta' => $entity->getVenta()->getId(),
                    'descripcion' => $entity->getDescripcion(),
                    'precio_unitario' => $entity->getPrecio_unitario(),
                    'id_tipo_ingreso' => $entity->getTipo()->getId(),
                    'id_producto' => $entity->getProducto()->getId()
        ));
    }

    /**
     * 
     */
    public function cancelIngreso(IngresoDetalle $entity) {
        $data = array('bajaLogica' => 1);
        return $this->update("DETALLE_INGRESO", $data, $entity->getId());
    }

    /**
     * Convierte un la Entidad de Arreglo a Clase
     * 
     * @param array $arrayEntity Arreglo de la Entidad
     * @return boolean False - Si no se encontró pudo recuperar las relaciones de la Entidad
     * @return IngresoDetalle Entidad convertida en Clase
     */
    private function getEntityByArray($arrayEntity) {
        $entity = new IngresoDetalle();
        $entity->setId($arrayEntity['id']);
        $entity->setCantidad($arrayEntity['cantidad']);
        $entity->setPrecio_unitario($arrayEntity['precio_unitario']);
        $entity->setDescripcion($arrayEntity['descripcion']);
        $entity->setFecha(DateTime::createFromFormat('Y-m-d H:i:s', $arrayEntity['fecha']));
        $entity->setBajaLogica($arrayEntity['bajaLogica']);
        /*if (!$venta = VentaModelOrm::getInstance()->getById($arrayEntity['id_venta'])) {
            return FALSE;
        }*/
        //$entity->setVenta($venta);
        if (!$tipo = IngresoTipoModelOrm::getInstance()->getById($arrayEntity['id_tipo_ingreso'])) {
            return FALSE;
        }
        $entity->setTipo($tipo);
        if (!$producto = ProductoModel::getInstance()->getById($arrayEntity['id_producto'])) {
            return FALSE;
        }
        $entity->setProducto($producto);
        return $entity;
    }

    /**
     * Obtiene el Ingreso detalle por el ID
     * 
     * @param integer $id Id del Ingreso Detalle
     * @return boolean False si no encontró el Ingreso detalle
     * @return IngresoDetalle Ingreso Detalle
     */
    public function getById($id) {
        $consulta = ""
                . "SELECT * "
                . "FROM DETALLE_INGRESO "
                . "WHERE id = :id";

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
     * Recupera todos los Ingresos del sistema activos
     * 
     * @return array  De entidades Ingresos Detalles almacenadas en la BD
     */
    public function getAll() {
        $consulta = ""
                . "SELECT * "
                . "FROM DETALLE_INGRESO "
                . "WHERE bajaLogica = 0 ";
        $result = $this->select($consulta);
        $arreglo = array();
        foreach ($result as $usuario) {
            $arreglo[] = $this->getEntityByArray($usuario);
        }
        return $arreglo;
    }

    /**
     * Recupera todos los Ingresos Detalles del sistema sin importar su estado
     * 
     * @return array De Ingresos Detalle almacenadas en la BD
     */
    public function getAllAnything() {
        $consulta = ""
                . "SELECT * "
                . "FROM DETALLE_INGRESO ";
        $result = $this->select($consulta);
        $arreglo = array();
        foreach ($result as $usuario) {
            $arreglo[] = $this->getEntityByArray($usuario);
        }
        return $arreglo;
    }

    /**
     * 
     */
    public function getByIdVenta($id_venta) {
        $consulta = ""
                . "SELECT * "
                . "FROM DETALLE_INGRESO "
                . "WHERE id_venta = :id_venta";
        $result = $this->select($consulta, array(
            'id_venta' => $id_venta
        ));
        $arreglo = array();
        foreach ($result as $elem) {
            $arreglo[] = $this->getEntityByArray($elem);
        }
        return $arreglo;
    }

}
