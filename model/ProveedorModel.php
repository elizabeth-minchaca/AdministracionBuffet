<?php
require_once (PATH_MODEL . 'PDORepository.php');
require_once (PATH_CLASS . 'Proveedor.php');

class ProveedorModel extends PDORepository {
	const TABLA = "PROVEEDOR";
	private static $instance;
	public static function getInstance() {
		if (! isset ( self::$instance )) {
			self::$instance = new self ();
		}
		return self::$instance;
	}
	public function getAll() {
		return $this->select ( "SELECT * FROM " . self::TABLA );
	}
	public function nueva($data) {
		return $this->insert ( self::TABLA, $data );
	}
	public function actualizar($id, $data) {
		return $this->update ( self::TABLA, $data, $id );
	}
	public function getById($id) {
		$consulta = "SELECT * FROM ". self::TABLA." AS pro " . "WHERE pro.id = :id ";
		$result = $this->select ( $consulta, array (
				'id' => $id
		) );
		if (count ( $result ) > 0) {
			$elemento = reset ( $result );
			return $this->armarProveedor( $elemento );
		} else {
			return false;
		}
	}
	
    //retorna un array de objetos productos
    public function getObjetosProveedores()
    {
    	$productos=self::getAll();
    	return self::arregloDeProveedores($productos);
    }
    
    private function arregloDeProveedores($tuplas) {
    	$arrayObjetos=array();
    	foreach ($tuplas as  $tupla)
    	{
    		$object=self::armarProveedor($tupla);
    		$arrayObjetos[]=$object;
    	}
    	return  $arrayObjetos;
    }
    
    private function armarProveedor($value)
    {
    	$proveedor=new Proveedor();
    	$proveedor->setId($value["id"]);
    	$proveedor->setNombre($value["nombre"]);
    	$proveedor->setCuit($value["cuit"]);    	
    	return $proveedor;
    }
}

