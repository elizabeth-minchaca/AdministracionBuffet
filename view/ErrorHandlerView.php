<?php

require_once(PATH_VIEW . 'TwigView.php');

class ErrorHandlerView extends TwigView {

    /**
     * Muestra la página de error al no tener acceso suficiente para visualizar el contenido
     */
    public function renderAcccesDenied($parameters = array()) {
        echo self::getTwig()->render('errorHandler_accessdenied.html.twig',$parameters);
    }

    /**
     * Muestra la página de error al no encotnrar un objeto requerido
     */
    public function renderNotFound($parameters = array()) {
        echo self::getTwig()->render('errorHandler_notfound.html.twig',$parameters);
    }

    /**
     * Muestra la página de error al no encotnrar un objeto requerido
     */
    public function renderDisabledPage($parameters = array()) {
        echo self::getTwig()->render('errorHandler_disabledSite.html.twig',$parameters);
    }
}
