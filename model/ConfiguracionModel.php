<?php

require_once (PATH_MODEL . 'PDORepository.php');
require_once (PATH_CLASS . 'Configuracion.php');

class ConfiguracionModel extends PDORepository {

    private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function actualizar(Configuracion $conf) {
        return $this->update('CONFIGURACION', array(
                    'titulo' => $conf->getTitulo(),
                    'sitioHabilitadoMsj' => $conf->getSitioHabilitadoMsj(),
                    'descripcion' => $conf->getDescripcion(),
                    'email' => $conf->getEmail(),
                    'sitioHabilitado' => $conf->getSitioHabilitado() ? 1 : 0,
                    'paginado_numero' => $conf->getPaginadoNumero()), $conf->getId());
    }

    public function getConfiguracion() {
        $result = $this->select(""
                . "SELECT * "
                . "FROM CONFIGURACION ");
        if (count($result) == 0) {
            return FALSE;
        }
        $config = reset($result);
        $configuracion = new Configuracion();
        $configuracion->setId($config['id']);
        $configuracion->setTitulo($config['titulo']);
        $configuracion->setDescripcion($config['descripcion']);
        $configuracion->setEmail($config['email']);
        $configuracion->setSitioHabilitado($config['sitioHabilitado']);
        $configuracion->setPaginadoNumero($config['paginado_numero']);
        $configuracion->setSitioHabilitadoMsj($config['sitioHabilitadoMsj']);
        return $configuracion;
    }

}
