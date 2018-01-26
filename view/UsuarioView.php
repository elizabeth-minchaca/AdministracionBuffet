<?php

require_once(PATH_VIEW . 'TwigView.php');

class UsuarioView extends TwigView {

    /**
     * 
     */
    public function renderNuevo($parameters = array()) {
        echo self::getTwig()->render('usuario_nuevo.html.twig', $parameters);
    }

    /**
     * 
     */
    public function renderListado($parameters = array()) {
        echo self::getTwig()->render('usuario_listado.html.twig', $parameters);
    }
    
    /**
     * 
     */
    public function renderListadoOnline($parameters = array()) {
        echo self::getTwig()->render('usuario_listado_online.html.twig', $parameters);
    }    

    /**
     * 
     */
    public function renderEditar($parameters = array()) {
        echo self::getTwig()->render('usuario_editar.html.twig', $parameters);
    }

    /**
     * 
     */
    public function renderShow($parameters = array()) {
        echo self::getTwig()->render('usuario_ver.html.twig', $parameters);
    }
}
