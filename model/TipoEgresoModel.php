<?php

require_once (PATH_MODEL . 'PDORepository.php');
require_once (PATH_CLASS . 'TipoEgreso.php');

class TipoEgresoModel extends PDORepository {

    const TABLA = "TIPO_EGRESO";

    private static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self ();
        }
        return self::$instance;
    }

    public function getAll() {
        $consulta = "SELECT * FROM " . self::TABLA;
        return $this->select($consulta);
    }

    // retorna un array de objetos tipo de egresos
    public function getObjetosTiposEgresos() {
        $egresos = self::getAll();
        return self::arregloTiposEgresos($egresos);
    }

    public function getById($id) {
        $consulta = "SELECT * FROM " . self::TABLA . " AS tabla " . "WHERE tabla.id = :id ";
        $result = $this->select($consulta, array(
            'id' => $id
        ));
        if (count($result) > 0) {
            $elemento = reset($result);
            return $this->armarTipoEgreso($elemento);
        } else {
            return false;
        }
    }

    public function getByNombre($nombre) {
        $consulta = "SELECT * FROM " . self::TABLA . " AS tabla " . "WHERE tabla.nombre = :nombre ";
        $result = $this->select($consulta, array(
            'nombre' => $nombre
        ));
        if (count($result) > 0) {
            $elemento = reset($result);
            return $this->armarTipoEgreso($elemento);
        } else {
            return false;
        }
    }
    
    private function arregloTiposEgresos($tuplas) {
        $arrayObjetos = array();
        foreach ($tuplas as $tupla) {
            $object = self::armarTipoEgreso($tupla);
            $arrayObjetos [] = $object;
        }
        return $arrayObjetos;
    }

    private function armarTipoEgreso($value) {
        $tipoEgreso = new TipoEgreso ();
        $tipoEgreso->setId($value ["id"]);
        $tipoEgreso->setNombre($value ["nombre"]);
        return $tipoEgreso;
    }

}
