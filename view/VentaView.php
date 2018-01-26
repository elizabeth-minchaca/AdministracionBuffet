<?php

require_once(PATH_VIEW . 'TwigView.php');

class VentaView extends TwigView {

    /**
     * 
     */
    public function renderNuevo($parameters = array()) {
        echo self::getTwig()->render('venta_nueva.html.twig', $parameters);
    }

    /**
     * 
     */
    public function renderListado($parameters = array()) {
        echo self::getTwig()->render('venta_listado.html.twig', $parameters);
    }
    
     /**
     * 
     */
    public function renderVer($parameters = array()) {
        echo self::getTwig()->render('venta_ver.html.twig', $parameters);
    }

}
