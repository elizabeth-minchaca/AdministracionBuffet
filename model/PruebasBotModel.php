<?php
require_once (PATH_MODEL . 'PDORepository.php');
require_once (PATH_CLASS . 'UsuarioBot.php');
class PruebasBotModel extends PDORepository {
	const TABLA = "USUARIO_BOT";
	private static $instance;
	public static function getInstance() {
		if (! isset ( self::$instance )) {
			self::$instance = new self ();
		}
		return self::$instance;
	}
	
	public function nuevo($data) {
		return $this->insert ( self::TABLA, $data );
	}
	
	public function existeUsuario($username, $firstname, $fromid){
		$sql = "SELECT * FROM ".self::TABLA." WHERE username = :username AND fromid = :fromid AND firstname = :firstname";
		$result = $this->select($sql, array(
				'username' => $username,
				'firstname' => $firstname,
				'fromid' => $fromid
		));
		if (count($result) > 0){
			$elemento = reset ( $result );
			return  $this->armarUsuarioBot ( $elemento );
		}else {
			return false;
		}
	}
	
	private function arregloDeUsuariosBot($tuplas) {
		$arrayObjetos = array ();
		foreach ( $tuplas as $tupla ) {
			$object = self::armarUsuarioBot( $tupla );
			$arrayObjetos [] = $object;
		}
		return $arrayObjetos;
	}
	
	private function armarUsuarioBot ($value) {
		$user = new UsuarioBot();
		$user->setId ( $value ["id"] );
		$user->setFirstName( $value ["firstname"] );
		$user->setUserName( $value ["username"] );
		$user->setFromId( $value ["fromid"] );
		return $user;
	}
	
	public function getUsuarioBotPag($nroPagina) {
		$where = ' ';
		$result = $this->getGenericoTablaPaginado ( self::TABLA, $nroPagina, $where, 'id', 'DESC' );
		return PruebasBotModel::getInstance()->arregloDeUsuariosBot( $result );
	}
	public function getCantPaginas() {
		return $this->getGenericoCantPaginas ( self::TABLA );
	}
	
	public function getAllUsuariosBot(){
		$sql = "SELECT * FROM ". self::TABLA;
		$result = $this->select($sql);
		return PruebasBotModel::getInstance()->arregloDeUsuariosBot( $result );
	}
}
