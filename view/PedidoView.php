<?php

require_once(PATH_VIEW . 'TwigView.php');

class PedidoView extends TwigView {

    /**
     * 
     */
    public function renderNuevo($parameters = array()) {
        echo self::getTwig()->render('pedido_nuevo.html.twig', $parameters);
    }

    /**
     * 
     */
    public function renderMisPedidos($parameters = array()) {
        echo self::getTwig()->render('pedido_mis_pedidos.html.twig', $parameters);
    }

    /**
     * 
     */
    public function renderVer($parameters = array()) {
        echo self::getTwig()->render('pedido_ver.html.twig', $parameters);
    }

    /**
     * 
     */
    public function renderCancel($parameters = array()) {
        echo self::getTwig()->render('pedido_cancel.html.twig', $parameters);
    }

    /**
     * 
     */
    public function renderPendientes($parameters = array()) {
        echo self::getTwig()->render('pedido_pendientes.html.twig', $parameters);
    }

    /**
     * 
     */
    public function renderTodos($parameters = array()) {
        echo self::getTwig()->render('pedido_todos.html.twig', $parameters);
    }

}
