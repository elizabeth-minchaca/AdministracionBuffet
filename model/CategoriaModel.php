<?php

require_once (PATH_MODEL . 'PDORepository.php');
require_once (PATH_CLASS . 'Categoria.php');

class CategoriaModel extends PDORepository {

	const TABLA = "CATEGORIA";
    private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
	public function getAll() {
		return $this->select ( "SELECT * FROM ". self::TABLA." as hoja WHERE hoja.id not in (SELECT subc.categoria_padre_id FROM ". self::TABLA." as subc WHERE subc.categoria_padre_id is NOT NULL GROUP BY subc.categoria_padre_id)" );
	}
	public function getById($id) {
		$consulta = "SELECT * FROM ". self::TABLA." AS cat " . "WHERE cat.id = :id AND cat.bajaLogica = 0";
		$result = $this->select ( $consulta, array (
				'id' => $id 
		) );
		if (count ( $result ) > 0) {
			$elemento = reset ( $result );
			return $this->armarCategoria ( $elemento );
		} else {
			return false;
		}
	}

    //retorna un array de objetos categoria hoja
    public function getObjetosCategoria()
    {
    	$categorias=self::getAll();
    	$arrayCategorias=array();
    	foreach ($categorias as  $categoria)
    	{
    		$objectCategoria=self::armarCategoria($categoria);
    		$arrayCategorias[]=$objectCategoria;
    	}
    	return  $arrayCategorias;
    }   
    
    private function armarCategoria($value)
    {
    	$categoria = new Categoria();
    	$categoria->setId($value['id']);
    	$categoria->setNombre($value['nombre']);
    	$idCategoriaPadre = $value['categoria_padre_id']; 
    	if($idCategoriaPadre != null || $idCategoriaPadre != "") {
    		$catPadre = self::getById($idCategoriaPadre);
    		$categoria->setCategoriaPadre($catPadre);
    	}    		
    	
    	return $categoria;
    }

}

