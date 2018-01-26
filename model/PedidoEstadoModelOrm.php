<?php

require_once(PATH_CLASS . 'PedidoEstado.php');
require_once (PATH_MODEL . 'PDORepository.php');

class PedidoEstadoModelOrm extends PDORepository {

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
    public function getByName($nombre) {
        $result = $this->select("SELECT * FROM PEDIDO_ESTADO WHERE bajaLogica = 0 AND nombre = :nombre ", array(
            'nombre' => $nombre
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
    public function getById($id) {
        $consulta = ""
                . "SELECT * "
                . "FROM PEDIDO_ESTADO "
                . "WHERE id = :id AND bajaLogica = 0 ";

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

    private function getEntityByArray($array) {
        $pedidoEstado = new PedidoEstado();
        $pedidoEstado->setBajaLogica($array['bajaLogica']);
        $pedidoEstado->setNombre($array['nombre']);
        $pedidoEstado->setId($array['id']);
        return $pedidoEstado;
    }

}
