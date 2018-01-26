<?php

require_once(PATH_VIEW . 'TwigView.php');

class ListadoView extends TwigView {

    public function renderProductosFanltantes($parameters = array()) {
        echo self::getTwig()->render('listado_productos_faltantes.html.twig', $parameters);
    }

    public function renderProductosStockMinimo($parameters = array()) {
        echo self::getTwig()->render('listado_productos_stock_minimo.html.twig', $parameters);
    }

    public function renderGananciasPorDia($parameters = array()) {
    	echo self::getTwig()->render('grafico_ganancias_dia.html.twig', $parameters);
    }
    
    public function renderVentasProductosRango($parameters = array()) {
    	echo self::getTwig()->render('grafico_ventas_productos.html.twig', $parameters);
    }
    //*********Listado de ventas
    public function renderListadoVentasProductos($parameters = array()) {
    	echo self::getTwig()->render('listado_ventas_productos.html.twig', $parameters);
    }
    
    public function renderListadoRangoVentasProductos($parameters = array()) {
    	return self::getTwig()->render('listado_producto_template.html.twig', $parameters);
    }

    public function renderPaginadorRangoVentasProductos($parameters = array()) {
    	return self::getTwig()->render('paginador_listado_productos.html.twig', $parameters);
    }
    //*********
    //*********Listado de Ganancias
    public function renderListadoGananciaPorDia($parameters = array()) {
    	echo self::getTwig()->render('listado_ganancia_por_dia.html.twig', $parameters);
    }
    
    public function renderListadoRangoGananciaPorDia($parameters = array()) {
    	return self::getTwig()->render('listado_ganancia_dia_template.html.twig', $parameters);
    }
    
    public function renderPaginadorRangoGananciaPorDia($parameters = array()) {
    	return self::getTwig()->render('paginador_listado_ganancia_dia.html.twig', $parameters);
    }
    //*********
    
}
