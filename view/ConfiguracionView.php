<?php
require_once(PATH_VIEW . 'TwigView.php');

class ConfiguracionView extends TwigView{
	
	public function renderFormConfiguracion($parameters = array()) {
		echo self::getTwig()->render('configuracion_editar.html.twig', $parameters);
	}

	public function renderMostrarConfiguracion($parameters = array()) {
		echo self::getTwig()->render('configuracion_ver.html.twig', $parameters);
	}
	
	
}