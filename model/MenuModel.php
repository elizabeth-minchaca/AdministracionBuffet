<?php

require_once (PATH_MODEL . 'PDORepository.php');
require_once (PATH_CLASS . 'Menu.php');
require_once (PATH_CLASS . 'DetalleMenu.php');

class MenuModel extends PDORepository {

    const TABLA = "MENU";

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

    public function eliminar($id) {
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
            return $this->armarMenu($elemento);
        } else {
            return false;
        }
    }

    public function getMenuDelDia(){
        
    }
    
    public function existeFecha($fecha) {
        $sql = "SELECT * FROM " . self::TABLA . " WHERE fecha = :fecha AND bajaLogica = 0";
        $result = $this->select($sql, array(
            'fecha' => $fecha
        ));
        if (count($result) > 0) {
            $elemento = reset($result);
            return $this->armarMenu($elemento);
        } else {
            return false;
        }
    }

    public function habilitar($id) {
        $data = array(
            'habilitada' => 1
        );
        return $this->update(self::TABLA, $data, $id);
    }

    private function arregloDeMenu($tuplas) {
        $arrayObjetos = array();
        foreach ($tuplas as $tupla) {
            $object = self::armarMenu($tupla);
            $arrayObjetos [] = $object;
        }
        return $arrayObjetos;
    }

    private function armarMenu($value) {
        $menu = new Menu ();
        $menu->setId($value ["id"]);
        $menu->setFecha($value ["fecha"]);
        $menu->setHabilitada($value ["habilitada"]);
        $detalles = DetalleMenuModel::getInstance()->getObjetosDetallesMenu($menu);
        $menu->setMenuDetalles($detalles);
        return $menu;
    }

    // USANDO PAGINADO GENERICO
    public function getMenuPag($nroPagina) {
        $where = ' WHERE bajaLogica = 0 ';
        $result = $this->getGenericoTablaPaginado(self::TABLA, $nroPagina, $where, 'id', 'DESC');
        return MenuModel::getInstance()->arregloDeMenu($result);
    }

    public function getCantPaginas() {
        $where = ' WHERE bajaLogica = 0 ';
        return $this->getGenericoCantPaginas(self::TABLA, $where);
    }

}
