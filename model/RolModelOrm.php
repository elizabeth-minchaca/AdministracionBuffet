<?php

require_once (PATH_MODEL . 'PDORepository.php');
require_once (PATH_CLASS . 'Rol.php');

class RolModelOrm extends PDORepository {

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
    public function insertEntity(Rol $aRol) {
        return $this->insert("ROL", array(
                    'nombre' => $aRol->getNombre()
        ));
    }

    /**
     *
     */
    public function deleteEntity(Rol $aRol) {
        return $this->delete("ROL", $aRol->getId());
    }

    /**
     * 
     */
    public function updateEntity(Rol $aRol) {
        return $this->update("ROL", array(
                    'nombre' => $aRol->getNombre(),
                    'bajaLogica' => $aRol->getBajaLogica()
                        ), $aRol->getId());
    }

    /**
     * Convierte un la Entidad de Arreglo a Clase
     * @param array $arrayEntity Arreglo de la Entidad
     * @return Rol Entidad convertida en Clase
     */
    private function getEntityByArray($arrayEntity) {
        $entidad = new Rol();
        $entidad->setBajaLogica($arrayEntity['bajaLogica']);
        $entidad->setId($arrayEntity['id']);
        $entidad->setNombre($arrayEntity['nombre']);
        return $entidad;
    }

    /**
     * Función que retorna todos los roles del sistema activos
     * 
     * @return boolean Si no encuentra el Rol
     */
    public function getAll() {
        $consulta = ""
                . "SELECT rol.id AS id, rol.nombre AS nombre, rol.bajaLogica as bajaLogica "
                . "FROM ROL AS rol "
                . "WHERE rol.bajaLogica = 0";
        $result = $this->select($consulta);
        $arreglo = array();
        foreach ($result as $unRol) {
            $arreglo[] = $this->getEntityByArray($unRol);
        }
        return $arreglo;
    }

    /**
     * Busqueda de un Rol mediante su ID
     * 
     * @param int $id Id del rol a conseguir
     * @return boolean Si no encuentra el Rol
     * @return Rol Si encontró el Rol
     */
    public function getById($id) {
        $consulta = ""
                . "SELECT * "
                . "FROM ROL AS rol "
                . "WHERE rol.id = :id";
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
     * Busqueda de un Rol mediante su Nombre
     * 
     * @param string $nombre Nombre del rol a conseguir
     * @return boolean Si no encuentra el Rol
     * @return Rol Si encontró el Rol
     */
    public function getByNombre($nombre) {
        $consulta = ""
                . "SELECT * "
                . "FROM ROL AS rol "
                . "WHERE rol.nombre = :nombre AND rol.bajaLogica = 0";
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
