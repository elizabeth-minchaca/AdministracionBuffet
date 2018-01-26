<?php
require_once(PATH_VIEW . 'TwigView.php');

class PruebasBotView extends TwigView {


	public function renderListadoUsuariosBot($parameters = array()) {
		echo self::getTwig()->render('usuario_telegram_listado.html.twig', $parameters);
	}

}