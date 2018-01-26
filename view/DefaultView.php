<?php

require_once(PATH_VIEW . 'TwigView.php');

class DefaultView extends TwigView {

    /**
     * 
     */
    public function renderIndex($parameters = array()) {
        echo self::getTwig()->render('index.html.twig', $parameters);
    }

    /**
     * 
     */
    public function renderHome($parameters = array()) {
        echo self::getTwig()->render('home.html.twig', $parameters);
    }
    
    /**
     * 
     */
    public function renderLogin($parameters = array()) {
        echo self::getTwig()->render('login.html.twig', $parameters);
    }

    /**
     * 
     */
    public function renderRegister($parameters = array()) {
        echo self::getTwig()->render('register.html.twig', $parameters);
    }
    
}
