<?php

require_once(PATH_CLASS . 'PedidoDetalle.php');
require_once (PATH_MODEL . 'PDORepository.php');

class PedidoDetalleModelOrm extends PDORepository {

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
    public function insertEntity(PedidoDetalle $pedidoDetalle) {
        return $this->insert("DETALLE_PEDIDO", array(
                    'id_pedido' => $pedidoDetalle->getId_pedido(),
                    'id_producto' => $pedidoDetalle->getProducto()->getId(),
                    'cantidad' => $pedidoDetalle->getCantidad(),
        ));
    }

    /**
     * 
     */
    public function deleteEntity(PedidoDetalle $pedidoDetalle) {
        return $this->delete("DETALLE_PEDIDO", $pedidoDetalle->getId());
    }

    /**
     * 
     */
    public function updateEntity(PedidoDetalle $pedidoDetalle) {
        return $this->update("DETALLE_PEDIDO", array(
                    'id_pedido' => $pedidoDetalle->getId_pedido(),
                    'id_producto' => $pedidoDetalle->getProducto()->getId(),
                    'cantidad' => $pedidoDetalle->getCantidad(),
                    'bajaLogica' => ($pedidoDetalle->getBajaLogica() ? 1 : 0), $pedidoDetalle->getId()
        ));
    }

    /**
     * 
     */
    public function getDetalles($id) {
        $consulta = ""
                . "SELECT * "
                . "FROM DETALLE_PEDIDO "
                . "WHERE bajaLogica = 0 AND id_pedido = :id_pedido ";
        $result = $this->select($consulta, array(
            'id_pedido' => $id
        ));
        $arreglo = array();
        foreach ($result as $detalle) {
            $arreglo[] = $this->getEntityByArray($detalle);
        }
        return $arreglo;
    }

    private function getEntityByArray($arrayEntity) {
        $detalle = new PedidoDetalle();
        $detalle->setBajaLogica($arrayEntity['bajaLogica']);
        $detalle->setCantidad($arrayEntity['cantidad']);
        $detalle->setId($arrayEntity['id']);
        $detalle->setId_pedido($arrayEntity['id_pedido']);
        if (!$producto = ProductoModel::getInstance()->getById($arrayEntity['id_producto'])) {
            return NULL;
        }
        $detalle->setProducto($producto);
        return $detalle;
    }

}
