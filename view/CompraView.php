<?php

require_once(PATH_VIEW . 'TwigView.php');

class CompraView extends TwigView {

    public function renderNuevaCompra($parameters = array()) {
        echo self::getTwig()->render('compra.html.twig', $parameters);
    }

    public function renderEditarCompra($parameters = array()) {
        echo self::getTwig()->render('compra_editar.html.twig', $parameters);
    }

    public function renderEditarCompraDetalleEgreso($parameters = array()) {
        echo self::getTwig()->render('compra_editar_detalle_egreso.html.twig', $parameters);
    }

    /**
     * 
     */
    public function renderNuevoEgresoDetalle($parameters = array()) {
        echo self::getTwig()->render('detalle_egreso_compra.html.twig', $parameters);
    }

    public function renderVerCompra($parameters = array()) {
        echo self::getTwig()->render('compra_ver.html.twig', $parameters);
    }

    public function renderMostrarCompras($parameters = array()) {
        echo self::getTwig()->render('mostrar_compras.html.twig', $parameters);
    }

    public function renderListadoCompras($parameters = array()) {
        echo self::getTwig()->render('compra_listado.html.twig', $parameters);
    }

}
