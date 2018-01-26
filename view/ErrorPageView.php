<?php

require_once(PATH_VIEW . 'TwigView.php');

class ErrorPageView extends TwigView {

    public function show($param = null) {
        if ($param) {
            echo self::getTwig()->render('errorPage.html.twig', array('mensaje' => $param['mensaje'],  'urlBack' => $param['urlBack']));
        } else {
            echo self::getTwig()->render('errorPage.html.twig');
        }
    }

}
