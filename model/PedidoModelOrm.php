<?php

require_once(PATH_CLASS . 'Pedido.php');
require_once (PATH_MODEL . 'PDORepository.php');
require_once (PATH_MODEL . 'PedidoDetalleModelOrm.php');
require_once (PATH_MODEL . 'PedidoEstadoModelOrm.php');
require_once (PATH_MODEL . 'UsuarioModelOrm.php');

class PedidoModelOrm extends PDORepository {

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
    public function insertEntity(Pedido $pedido) {
        return $this->insert("PEDIDO", array(
                    'id_estado' => $pedido->getEstado()->getId(),
                    'id_usuario' => $pedido->getRealizadoPor()->getId(),
                    'observacion' => $pedido->getObservacion()));
    }

    /**
     * 
     */
    public function deleteEntity(Pedido $pedido) {
        return $this->delete("PEDIDO", $pedido->getId());
    }

    /**
     * 
     */
    public function updateEntity(Pedido $pedido) {
        return $this->update("PEDIDO", array(
                    'id_estado' => $pedido->getEstado()->getId(),
                    'observacion' => $pedido->getObservacion(),
                    'bajaLogica' => ($pedido->getBajaLogica() ? 1 : 0)), $pedido->getId());
    }

    /**
     * 
     */
    public function getById($id) {
        $result = $this->select("SELECT * FROM  PEDIDO WHERE bajaLogica = 0 AND id = :id", array(
            'id' => $id
        ));
        if (count($result) > 0) {
            $elemento = reset($result);
            return $this->getEntityByArray($elemento);
        } else {
            return false;
        }
    }

    /**
     * 
     */
    private function getEntityByArray($arrayEntity) {
        $pedido = new Pedido();
        $pedido->setBajaLogica($arrayEntity['bajaLogica']);
        $pedido->setFecha(DateTime::createFromFormat('Y-m-d H:i:s', $arrayEntity['fecha_alta']));
        $pedido->setObservacion($arrayEntity['observacion']);
        $pedido->setId($arrayEntity['id']);
        $detalles = PedidoDetalleModelOrm::getInstance()->getDetalles($arrayEntity['id']);
        $pedido->setDetalles($detalles);
        if (!$estado = PedidoEstadoModelOrm::getInstance()->getById($arrayEntity['id_estado'])) {
            return NULL;
        }
        $pedido->setEstado($estado);
        if (!$usuario = UsuarioModelOrm::getInstance()->getById($arrayEntity['id_usuario'])) {
            return NULL;
        }
        $pedido->setRealizadoPor($usuario);
        return $pedido;
    }

    /**
     * 
     */
    public function getMyOrdersPag($nroPagina, $user) {
        $result = $this->getGenericoTablaPaginado('PEDIDO', $nroPagina, 'WHERE id_usuario = '.$user->getId(), 'fecha_alta', 'DESC');
        $arreglo = array();
        foreach ($result as $usuario) {
            $arreglo[] = $this->getEntityByArray($usuario);
        }
        return $arreglo;
    }

    /**
     * 
     */
    public function getMyOrdersCantPaginas($user) {
        return $this->getGenericoCantPaginas('PEDIDO', 'WHERE id_usuario = '.$user->getId());
    }
    
    /**
     * 
     */
    public function getPendingPag($nroPagina) {
        $estado = PedidoEstadoModelOrm::getInstance()->getByName('PENDIENTE');
        $result = $this->getGenericoTablaPaginado('PEDIDO', $nroPagina, 'WHERE id_estado = '.$estado->getId(), 'fecha_alta', 'DESC');
        $arreglo = array();
        foreach ($result as $usuario) {
            $arreglo[] = $this->getEntityByArray($usuario);
        }
        return $arreglo;
    }

    /**
     * 
     */
    public function getPendingCantPaginas() {
        $estado = PedidoEstadoModelOrm::getInstance()->getByName('PENDIENTE');
        return $this->getGenericoCantPaginas('PEDIDO', 'WHERE id_estado = '.$estado->getId());
    }
    
    /**
     * 
     */
    public function getAllPag($nroPagina) {
        $estado = PedidoEstadoModelOrm::getInstance()->getByName('PENDIENTE');
        $result = $this->getGenericoTablaPaginado('PEDIDO', $nroPagina, NULL, 'fecha_alta', 'DESC');
        $arreglo = array();
        foreach ($result as $usuario) {
            $arreglo[] = $this->getEntityByArray($usuario);
        }
        return $arreglo;
    }

    /**
     * 
     */
    public function getAllCantPaginas() {
        $estado = PedidoEstadoModelOrm::getInstance()->getByName('PENDIENTE');
        return $this->getGenericoCantPaginas('PEDIDO');
    }

}
