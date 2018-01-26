<?php

require_once (PATH_MODEL . 'PDORepository.php');
require_once (PATH_MODEL . 'ProveedorModel.php');
require_once (PATH_MODEL . 'DetalleEgresoModel.php');
require_once (PATH_CLASS . 'Compra.php');
require_once (PATH_CLASS . 'EgresoDetalle.php');

class CompraModel extends PDORepository {

    const TABLA = "COMPRA";

    private static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self ();
        }
        return self::$instance;
    }

    public function nueva($data) {
        return $this->insert(self::TABLA, $data);
    }

    public function actualizar($id, $data) {
        return $this->update(self::TABLA, $data, $id);
    }

    public function eliminarCompra($id) {
        $data = array(
            'bajaLogica' => '1'
        );
        $resultado = self::actualizar($id, $data);
        return $resultado;
    }

    public function getById($id) {
        $consulta = "SELECT * FROM " . self::TABLA . " WHERE bajaLogica = 0 AND id = :id ";
        $result = $this->select($consulta, array(
            'id' => $id
                ));
        if (count($result) > 0) {
            $elemento = reset($result);
            return $this->armarCompra($elemento);
        } else {
            return false;
        }
    }

    public function finalizarCompra($id) {
        $data = array(
            'finalizada' => 1
        );
        return $this->update(self::TABLA, $data, $id);
    }

    private function armarCompra($value) {
        $compra = new Compra ();
        $compra->setId($value ["id"]);
        if (!$proveedor = ProveedorModel::getInstance()->getById($value ["id_proveedor"]))
            return FALSE;
        $compra->setProveedor($proveedor);
        $compra->setFecha($value ["fecha"]);
        $compra->setFinalizada($value ["finalizada"]);
        $compra->setFactura(base64_encode($value["factura"]));
        $detalles = DetalleEgresoModel::getInstance()->getObjetosEgresosDetalles($compra);
        $compra->setEgresosDetalles($detalles);
        return $compra;
    }

    private function arregloDeCompras($tuplas) {
        $arrayObjetos = array();
        foreach ($tuplas as $tupla) {
            $object = self::armarCompra($tupla);
            $arrayObjetos[] = $object;
        }
        return $arrayObjetos;
    }

    //USANDO PAGINADO GENERICO
    public function getComprasPag($nroPagina) {
        $where = ' WHERE bajaLogica = 0 ';
        $result = $this->getGenericoTablaPaginado(self::TABLA, $nroPagina, $where, 'id', 'DESC');
        return CompraModel::getInstance()->arregloDeCompras($result);
    }

    public function getCantPaginas() {
        $where = ' WHERE bajaLogica = 0 ';
        return $this->getGenericoCantPaginas(self::TABLA, $where);
    }
    public function getMontoEgresosPorFecha($fecha) {
    
    	$consulta = "SELECT IFNULL(sum(detalle.precio_unitario * detalle.cantidad),0) AS MONTO FROM " . self::TABLA . " compra INNER JOIN " . DetalleEgresoModel::TABLA . " detalle ON compra.id = detalle.id_compra WHERE compra.bajaLogica = 0 AND compra.finalizada = 1 AND DATE_FORMAT(compra.fecha,'%d/%m/%Y') = :fecha AND detalle.bajaLogica = 0  ";
    	$result = $this->select ( $consulta, array (
    			'fecha' => $fecha
    	) );
    	return $result[0]['MONTO'];
    }
}
