<?php

require_once (PATH_MODEL . 'PDORepository.php');
require_once (PATH_MODEL . 'CategoriaModel.php');
require_once (PATH_CLASS . 'Producto.php');

class ProductoModel extends PDORepository {

    const TABLA = "PRODUCTO";

    private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getAll() {
        return $this->select("SELECT * FROM " . self::TABLA . " WHERE bajaLogica = 0 ");
    }

    public function getAllOrder($orden) {
        return $this->select("SELECT * FROM " . self::TABLA . " WHERE bajaLogica = 0 " . $orden);
    }

    public function nueva($data) {
        return $this->insert(self::TABLA, $data);
    }

    public function actualizar($id, $data) {
        return $this->update(self::TABLA, $data, $id);
    }

    public function getById($id) {
        $consulta = "SELECT * FROM " . self::TABLA . " WHERE  id = :id ";
        $result = $this->select($consulta, array(
            'id' => $id
        ));
        if (count($result) > 0) {
            $elemento = reset($result);
            return $this->armarProducto($elemento);
        } else {
            return false;
        }
    }
    
    public function getByIdSinBajaLogica($id) {
    	$consulta = "SELECT * FROM " . self::TABLA . " WHERE id = :id ";
    	$result = $this->select($consulta, array(
    			'id' => $id
    	));
    	if (count($result) > 0) {
    		$elemento = reset($result);
    		return $this->armarProducto($elemento);
    	} else {
    		return false;
    	}
    }
    
    public function decrementarStock($id, $cantidad) {
        $producto = $this->getById($id);
        $data = array(
            'stock' => $producto->getStock() - $cantidad
        );
        return $this->update(self::TABLA, $data, $id);
    }     
    
    public function incrementarStock($id, $cantidad) {
        $producto = $this->getById($id);
        $data = array(
            'stock' => $producto->getStock() + $cantidad
        );
        return $this->update(self::TABLA, $data, $id);
    }                                                                                                                                   

    public function getByCodigoBarra($codigoBarra) {
        $consulta = "SELECT * FROM " . self::TABLA . " AS tabla " . "WHERE tabla.codigo_barra = :codigo_barra ";
        $result = $this->select($consulta, array(
            'codigo_barra' => $codigoBarra
        ));
        if (count($result) > 0) {
            $elemento = reset($result);
            return $this->armarProducto($elemento);
        } else {
            return false;
        }
    }

    public function existByCodigoBarra($codigoBarra) {
        $consulta = "SELECT nombre FROM " . self::TABLA . " AS tabla " . "WHERE tabla.codigo_barra = :codigo_barra ";
        $result = $this->select($consulta, array(
            'codigo_barra' => $codigoBarra
        ));
        if (count($result) > 0)
            return true;
        return false;
    }
    
    public function existeEnCompraPendiente($idProducto) {
    	$consulta = "SELECT compra.id FROM COMPRA AS compra INNER JOIN DETALLE_EGRESO AS det ON "
    			."det.id_compra = compra.id INNER JOIN " . self::TABLA . " AS tabla ON tabla.id = det.id_producto "
    			."WHERE tabla.id = :id AND compra.finalizada = 0 AND det.bajaLogica = 0 AND compra.bajaLogica = 0 ";
    	$result = $this->select($consulta, array(
    			'id' => $idProducto
    	));
    	if (count($result) > 0) 
    		return true;
    	return false;
    	
    }

    //retorna un array de objetos productos
    public function getObjetosProductos() {
        $orden = "ORDER BY `nombre` ASC";
        $productos = self::getAllOrder($orden);
        return self::arregloDeProductos($productos);
    }

    public function arregloDeProductos($productos) {
        $arrayProductos = array();
        foreach ($productos as $producto) {
            $objectProducto = self::armarProducto($producto);
            $arrayProductos[] = $objectProducto;
        }
        return $arrayProductos;
    }

    private function armarProducto($value) {
        $producto = new Producto();
        $producto->setId($value["id"]);
        $producto->setNombre($value["nombre"]);
        $producto->setMarca($value["marca"]);
        $producto->setCodigoBarra($value["codigo_barra"]);
        $producto->setStock($value["stock"]);
        $producto->setStockMinimo($value["stock_minimo"]);
        if (!$categoria = CategoriaModel::getInstance()->getById($value["id_categoria"]))
            return FALSE;
        $producto->setCategoria($categoria);
        $producto->setPrecioVentaUnitario($value["precio_venta_unitario"]);
        $producto->setDescripcion($value["descripcion"]);
        $producto->setFechaAlta($value["fecha_alta"]);

        return $producto;
    }

    public function eliminarProducto($id) {
        $data = array(
            'bajaLogica' => '1'
        );
        $resultado = self::actualizar($id, $data);
        return $resultado;
    }

    //dado un id de producto te retorna un objeto producto
    public function getObjetoProducto($id) {
        $value = self::getValueById(self::TABLA, $id);
        return self::armarProducto($value);
    }
    
    //USANDO PAGINADO GENERICO
    public function getProductosPag($nroPagina) {
    	$where = ' WHERE bajaLogica = 0 ';
    	$result = $this->getGenericoTablaPaginado(self::TABLA, $nroPagina, $where, 'id', 'DESC');
        return ProductoModel::getInstance()->arregloDeProductos($result);
    }
    
    public function getCantPaginas() {
    	$where = ' WHERE bajaLogica = 0 ';
    	return $this->getGenericoCantPaginas(self::TABLA, $where);
    }
    
}
