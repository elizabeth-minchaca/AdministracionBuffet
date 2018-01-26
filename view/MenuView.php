<?php
require_once(PATH_VIEW . 'TwigView.php');

class MenuView extends TwigView {


	public function renderNuevoMenu($parameters = array()) {
		echo self::getTwig()->render('menu_nuevo.html.twig', $parameters);
	}

	public function renderEditarMenu($parameters = array()) {
		echo self::getTwig()->render('menu_editar.html.twig', $parameters);
	}

	public function renderEditarDetalleMenu($parameters = array()){
		echo self::getTwig()->render('menu_editar_detalle.html.twig', $parameters);
	}
	/**
	 *
	 */
	public function renderNuevoDetalle($parameters = array()) {
		echo self::getTwig()->render('menu_nuevo_detalle.html.twig', $parameters);
	}
	public function renderVerMenu($parameters = array()) {
		echo self::getTwig()->render('menu_ver.html.twig', $parameters);
	}

	public function renderMostrarCompras($parameters = array()) {
		echo self::getTwig()->render('mostrar_compras.html.twig', $parameters);
	}

	public function renderListadoMenus($parameters = array()) {
		echo self::getTwig()->render('menu_listado.html.twig', $parameters);
	}

}