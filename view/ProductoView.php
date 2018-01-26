<?php

require_once(PATH_VIEW . 'TwigView.php');

class ProductoView extends TwigView {


    public function renderNuevo($parameters = array()) {
        echo self::getTwig()->render('producto_nuevo.html.twig',$parameters);
    }
    
    public function renderVer($parameters = array()) {
    	echo self::getTwig()->render('producto_ver.html.twig',$parameters);
    }
    public function renderEditar($parameters = array()) {
    	echo self::getTwig()->render('producto_editar.html.twig',$parameters);
    }
    
    public function renderListado($parameters = array()) {
        echo self::getTwig()->render('producto_listado.html.twig',$parameters);
    }
        
}
