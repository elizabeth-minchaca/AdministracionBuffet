<?php

require_once (PATH_MODEL . 'PDORepository.php');
require_once (PATH_MODEL . 'ProveedorModel.php');
require_once (PATH_MODEL . 'TipoEgresoModel.php');
require_once (PATH_CLASS . 'EgresoDetalle.php');

class DetalleEgresoModel extends PDORepository {

    const TABLA = "DETALLE_EGRESO";

    private static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self ();
        }
        return self::$instance;
    }

    public function getAll($idCompra) {
        return $this->select("SELECT * FROM " . self::TABLA . " WHERE bajaLogica= 0 AND id_compra =" . $idCompra);
    }

    public function nueva($data) {
        return $this->insert(self::TABLA, $data);
    }

    public function actualizar($id, $data) {
        return $this->update(self::TABLA, $data, $id);
    }

    public function eliminarDetalle($id) {
        $data = array(
            'bajaLogica' => '1'
        );
        $resultado = self::actualizar($id, $data);
        return $resultado;
    }

    // retorna un array de objetos detalles egresos
    public function getObjetosEgresosDetalles(Compra $compra) {
        $egresos = self::getAll($compra->getId());
        return self::arregloDeDetallesEgresos($egresos, $compra);
    }

    public function getById($id) {
        $consulta = "SELECT * FROM " . self::TABLA . " WHERE bajaLogica = 0 AND id = :id ";
        $result = $this->select($consulta, array(
            'id' => $id
                ));
        if (count($result) > 0) {
            $elemento = reset($result);
            return $this->armarEgresoDetalle($elemento);
        } else {
            return false;
        }
    }

    private function arregloDeDetallesEgresos($tuplas, Compra $compra) {
        $arrayObjetos = array();
        foreach ($tuplas as $tupla) {
            $object = self::armarEgresoDetalle($tupla, $compra);
            $arrayObjetos [] = $object;
        }
        return $arrayObjetos;
    }

    private function armarEgresoDetalle($value, Compra $compra = NULL) {
        $detalle = new EgresoDetalle ();
        $detalle->setId($value ["id"]);
        if (!$producto = ProductoModel::getInstance()->getByIdSinBajaLogica($value["id_producto"]))
            return FALSE;
        $detalle->setProducto($producto);
        $detalle->setCantidad($value ["cantidad"]);
        $detalle->setPrecioUnitario($value ["precio_unitario"]);
        if (!$tipoEgreso = TipoEgresoModel::getInstance()->getById($value["id_tipo_egreso"]))
            return FALSE;
        $detalle->setEgresoTipo($tipoEgreso);
        $detalle->setFecha($value ["fecha"]);
        if ($compra == NULL) {
            if (!$compraRecuperada = CompraModel::getInstance()->getById($value["id_compra"]))
                return FALSE;
            $detalle->setCompra($compraRecuperada);
        } else
            $detalle->setCompra($compra);
        return $detalle;
    }

}
