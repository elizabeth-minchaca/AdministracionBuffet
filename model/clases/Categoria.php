<?php

class Categoria {

    private $id;
    private $nombre;
    private $categoriaPadre = NULL;

    function getId() {
        return $this->id;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getCategoriaPadre() {
        return $this->categoriaPadre;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setCategoriaPadre(Categoria $categoriaPadre) {
        $this->categoriaPadre = $categoriaPadre;
    }

    function verCadenaCategorias() {
        $padre = $this->getCategoriaPadre();
        $cadena = $this->getNombre();
        while ($padre != NULL) {
            $cadena = $padre->getNombre() . " -> " . $cadena;
            $padre = $padre->getCategoriaPadre();
        }
        return $cadena;
    }

}
