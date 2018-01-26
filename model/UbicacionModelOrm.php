<?php

require_once (PATH_MODEL . 'PDORepository.php');
require_once (PATH_CLASS . 'Ubicacion.php');

class UbicacionModelOrm extends PDORepository {

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
    public function insertEntity(Ubicacion $ubicacion) {
        return $this->insert("UBICACION", array(
                    'nombre' => $ubicacion->getNombre()
        ));
    }

    /**
     *
     */
    public function deleteEntity($id) {
        return $this->delete("UBICACION", array(
                    'id' => $id
        ));
    }

    /**
     * 
     */
    public function updateEntity(Ubicacion $ubicacion) {
        return $this->update("UBICACION", array(
                    'nombre' => $ubicacion->getNombre(),
                    'bajaLogica' => $ubicacion->getBajaLogica()
                        ), $ubicacion->getId());
    }

    /**
     * Convierte un la Entidad de Arreglo a Clase
     * @param array $arrayEntity Arreglo de la Entidad
     * @return Ubicacion Entidad convertida en Clase
     */
    private function getEntityByArray($arrayEntity) {
        $entidad = new Ubicacion();
        $entidad->setBajaLogica($arrayEntity['bajaLogica']);
        $entidad->setId($arrayEntity['id']);
        $entidad->setNombre($arrayEntity['nombre']);
        return $entidad;
    }

    /**
     * Retorna todas las ubicaciones de la BD activas
     * 
     * @return array Arreglo de las entidades Ubicación
     */
    public function getAll() {
        $consulta = ""
                . "SELECT * "
                . "FROM UBICACION AS u "
                . "WHERE u.bajaLogica = 0";
        $result = $this->select($consulta);
        $arreglo = array();
        foreach ($result as $unaUbi) {
            $arreglo[] = $this->getEntityByArray($unaUbi);
        }
        return $arreglo;
    }

    /**
     * Busqueda de una Ubicación mediante su ID
     * 
     * @param int $id Id de la Ubicacion a conseguir
     * @return boolean Si no encuentra la Ubicacion
     * @return Ubicacion Si encontró el Rol
     */
    public function getById($id) {
        $consulta = ""
                . "SELECT * "
                . "FROM UBICACION AS u "
                . "WHERE u.id = :id";
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
     * Busqueda de una Ubicación mediante su Nombre
     * 
     * @param string $nombre Nombre de la Ubicación a conseguir
     * @return boolean Si no encuentra la Ubicacion
     * @return Ubicacion Si encontró la Ubicación
     */
    public function getByNombre($nombre) {
        $consulta = ""
                . "SELECT * "
                . "FROM UBICACION AS u "
                . "WHERE u.nombre = :nombre AND u.bajaLogica = 0";
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
