<?php

require_once (PATH_MODEL . 'PDORepository.php');
require_once (PATH_CLASS . 'Menu.php');
require_once (PATH_CLASS . 'DetalleMenu.php');
require_once (PATH_MODEL . 'ProductoModel.php');
require_once (PATH_MODEL . 'MenuModel.php');

class DetalleMenuModel extends PDORepository {

    const TABLA = "DETALLE_MENU";

    private static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self ();
        }
        return self::$instance;
    }

    public function getAll($idMenu) {
        return $this->select("SELECT * FROM " . self::TABLA . " WHERE bajaLogica= 0 AND id_menu =" . $idMenu);
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

    // retorna un array de objetos detalles menu
    public function getObjetosDetallesMenu(Menu $menu) {
        $detalles = self::getAll($menu->getId());
        return self::arregloDeDetallesMenu($detalles, $menu);
    }

    public function getById($id) {
        $consulta = "SELECT * FROM " . self::TABLA . " WHERE bajaLogica = 0 AND id = :id ";
        $result = $this->select($consulta, array(
            'id' => $id
                ));
        if (count($result) > 0) {
            $elemento = reset($result);
            return $this->armarDetalleMenu($elemento);
        } else {
            return false;
        }
    }

    private function arregloDeDetallesMenu($tuplas, Menu $menu) {
        $arrayObjetos = array();
        foreach ($tuplas as $tupla) {
            $object = self::armarDetalleMenu($tupla, $menu);
            $arrayObjetos [] = $object;
        }
        return $arrayObjetos;
    }

    private function armarDetalleMenu($value, Menu $menu = NULL) {
        $detalle = new DetalleMenu ();
        $detalle->setId($value ["id"]);
        if (!$producto = ProductoModel::getInstance()->getByIdSinBajaLogica($value ["id_producto"]))
            return FALSE;
        $detalle->setProducto($producto);
        $detalle->setCantidad($value ["cantidad"]);
        // $detalle->setFecha ( $value ["fecha"] );
        if ($menu == NULL) {
            if (!$menuRecuperado = MenuModel::getInstance()->getById($value ["id_menu"]))
                return FALSE;
            $detalle->setMenu($menuRecuperado);
        } else
            $detalle->setMenu($menu);
        return $detalle;
    }

}
