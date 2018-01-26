<?php
class UsuarioBot{
	private $id;
	private $fromid;
	private $firstname;
	private $username;
	
	function getId() {
		return $this->id;
	}
	
	function getFromId() {
		return $this->fromid;
	}
	
	function getFirstName() {
		return $this->firstname;
	}
	
	function getUserName() {
		return $this->username;
	}
	
	function setId($id) {
		$this->id = $id;
	}
	
	function setFromId($fromid) {
		$this->fromid = $fromid;
	}
	
	function setFirstName($firstname) {
		$this->firstname = $firstname;
	}
	
	function setUserName($username) {
		$this->username = $username;
	}
	
}	