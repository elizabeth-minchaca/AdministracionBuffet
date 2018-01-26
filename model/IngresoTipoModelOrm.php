<?php

require_once (PATH_MODEL . 'PDORepository.php');
require_once (PATH_CLASS . 'TipoIngreso.php');

class IngresoTipoModelOrm extends PDORepository {

    private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

  

    /**
     * Convierte la Entidad de Arreglo a Clase
     * @param array $arrayEntity Arreglo de la Entidad
     * @return TipoIngreso Entidad convertida en Clase
     */
    private function getEntityByArray($arrayEntity) {
        $entidad = new TipoIngreso();
        $entidad->setBajaLogica($arrayEntity['bajaLogica']);
        $entidad->setId($arrayEntity['id']);
        $entidad->setNombre($arrayEntity['nombre']);
        return $entidad;
    }

    /**
     * Retorna todas los tipos de ingresos ctivos
     * 
     * @return array Arreglo de las entidades TipoIngreso
     */
    public function getAll() {
        $consulta = ""
                . "SELECT * "
                . "FROM TIPO_INGRESO "
                . "WHERE bajaLogica = 0";
        $result = $this->select($consulta);
        $arreglo = array();
        foreach ($result as $entity) {
            $arreglo[] = $this->getEntityByArray($entity);
        }
        return $arreglo;
    }

    /**
     * Busqueda de un Tipo de Ingreso mediante su ID
     * 
     * @param int $id Id del Tipo a conseguir
     * @return boolean Si no lo encuentra 
     * @return TipoIngreso Si encontró el Tipo de Ingreso
     */
    public function getById($id) {
        $consulta = ""
                . "SELECT * "
                . "FROM TIPO_INGRESO "
                . "WHERE id = :id AND bajaLogica = 0";
        $result = $this->select($consulta, array(
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
     * Busqueda de un Tipo de Ingreso mediante su Nombre
     * 
     * @param string $nombre Nombre del Tipo a conseguir
     * @return boolean Si no lo encuentra
     * @return TipoIngreso Si encontró el Tipo de Ingreso
     */
    public function getByNombre($nombre) {
        $consulta = ""
                . "SELECT * "
                . "FROM TIPO_INGRESO "
                . "WHERE nombre = :nombre AND bajaLogica = 0";
        $result = $this->select($consulta, array(
            'nombre' => $nombre
        ));
        if (count($result) > 0) {
            $elemento = reset($result);
            return $this->getEntityByArray($elemento);
        } else {
            return false;
        }
    }

}
