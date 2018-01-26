<?php

require_once(PATH_CONTROLLER . 'SessionController.php');
require_once(PATH_CLASS . 'Configuracion.php');

class Controller {

    /**
     * Verifica si el string esta un email válido
     * 
     * @param string $email String a validar
     * @return boolean True si es válido. False en caso contrario
     */
    protected function isEmail($email) {
        return (filter_var($email, FILTER_VALIDATE_EMAIL)) ? TRUE : FALSE;
    }

    /**
     * Verifica si el parmámetro es del tipo Boolean
     * 
     * @param boolean $boolean Boolean a validar
     * @return boolean True si es un boolean. False en caso contrario
     */
    protected function isBoolean($boolean) {
        return (filter_var($boolean, FILTER_VALIDATE_BOOLEAN, array('flags' => FILTER_NULL_ON_FAILURE)) !== NULL) ? TRUE : FALSE;
    }

    /**
     * Verifica si el parámetro es del tipo Integer
     * 
     * @param integer $integer Integer a validar
     * @return boolean True si es un Integer. False en caso contrario
     */
    protected function isInteger($integer) {
        return (filter_var($integer, FILTER_VALIDATE_INT)) ? TRUE : FALSE;
    }

    /**
     * Verifica si el parámetro es del tipo Float
     * 
     * @param float $float Float a validar
     * @return boolean True si es un Float. False en caso contrario
     */
    protected function isFloat($float) {
        return (filter_var($float, FILTER_VALIDATE_FLOAT)) ? TRUE : FALSE;
    }

    /**
     * Verifica si el parámetro es una URL válida
     * 
     * @param string $url Url a validar
     * @return boolean True si es una URL válida. False en caso contrario
     */
    protected function isUrl($url) {
        return (filter_var($url, FILTER_VALIDATE_URL)) ? TRUE : FALSE;
    }

    /**
     * Verifica si el parámetro es una Pasword segura.
     * Una Password segura debe estar compuesta de:
     * - Al menos 6 caracteres
     * - Al menos una Mayúscula
     * - Al menos un dígito
     * 
     * @param string $password Password a validar
     * @return boolean True si es la password es válida. False en caso contrario
     */
    protected function isPasswordSecured($password) {
        return (preg_match("/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/", $password)) ? TRUE : FALSE;
    }

    /**
     * Verifica si el string esta vacio
     * 
     * @param string $str String a validar
     * @return boolean True si esta vacio. False en caso contrario
     */
    protected function isEmpty($str) {
        return ($str == NULL or $str == '') ? TRUE : FALSE;
    }

    /**
     * Verifica que el string ingresado tenga formato de fecha.
     * 
     * @param string $str
     * @return boolean
     */
    protected function isDate($str) {
        trim($str);
        $parts = explode("/", $str);
        if (count($parts) != 3)
            return false;
        $año = $parts[2];
        $mes = $parts[1];
        $dia = $parts[0];
        if (checkdate($mes, $dia, $año)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Verifica si el usuario loguado tiene al menos unos de los roles que se reciben como parámetros
     * 
     * @param array $roles Arreglo de string con los roles a verificar
     * @return boolean True si tiene alguno de los roles especificados. False en caso contrario.
     */
    protected function hasAccess($roles = array('ADMINISTRADOR', 'GESTION', 'USUARIO ONLINE')) {
        if (!SessionController::getInstance()->isLoginAction()) {
            return FALSE;
        }
        foreach ($roles as $rol) {
            if (SessionController::getInstance()->hasRolAction($rol)) {
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     *  Validador de Acceso con manejo de Errores
     * 
     * Si no cumple con alguno de los roles que se envia como parámetro, se dispara el Error correspondiente y se termina con la ejecución del sistema
     * 
     * @param array $roles Arreglo de string con los roles a verificar
     * @return boolean True si tiene alguno de los roles especificados.
     */
    protected function validateAccess($roles = array('ADMINISTRADOR', 'GESTION', 'USUARIO ONLINE')) {
        if (!$this->hasAccess($roles)) {
            $this->goAccessDeniedPage();
        }
        $conf = ConfiguracionModel::getInstance()->getConfiguracion();
        if (!$conf->getSitioHabilitado() && !SessionController::getInstance()->hasRolAction('ADMINISTRADOR')) {
            $this->goDisabledPageAction();
        }
        return TRUE;
    }

    /**
     * 
     */
    protected function validateCsrf() {
        if (!isset($_POST['token_csrf']) || SessionController::getInstance()->getTokenCSRF() != $_POST['token_csrf']) {
            $this->goCsrfError();
            return FALSE;
        }
        return TRUE;
    }

    /*
     * **************************************************************************
     * Disparadores de Errores
     */

    /**
     * 
     */
    protected function goNotFound() {
        ErrorHandlerController::getInstance()->notFoundAction();
        die();
    }

    /**
     * 
     */
    protected function goAccessDeniedPage() {
        SessionController::getInstance()->logoutAction();
        ErrorHandlerController::getInstance()->accessDeniedPageAction();
        die();
    }

    /**
     * 
     */
    protected function goDisabledPageAction() {
        ErrorHandlerController::getInstance()->disabledPageAction();
        die();
    }

    /**
     * 
     */
    protected function goCsrfError() {
        SessionController::getInstance()->logoutAction();
        ErrorHandlerController::getInstance()->csrfError();
        die();
    }

    /**
     * **************************************************************************
     */
}
