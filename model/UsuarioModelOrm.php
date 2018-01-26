<?php

require_once (PATH_MODEL . 'PDORepository.php');
require_once (PATH_MODEL . 'UbicacionModelOrm.php');
require_once (PATH_MODEL . 'RolModelOrm.php');
require_once (PATH_CLASS . 'Usuario.php');

class UsuarioModelOrm extends PDORepository {

    private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * 
     */
    public function insertEntity(Usuario $usuario) {
        return $this->insert("USUARIO", array(
                    'usuario' => $usuario->getUsuario(),
                    'clave' => $usuario->getClave(),
                    'nombre' => $usuario->getNombre(),
                    'apellido' => $usuario->getApellido(),
                    'documento' => $usuario->getDocumento(),
                    'email' => $usuario->getEmail(),
                    'telefono' => $usuario->getTelefono(),
                    'habilitado' => $usuario->getHabilitado() ? 1 : 0,
                    'id_rol' => ($usuario->getRol()) ? $usuario->getRol()->getId() : null,
                    'id_ubicacion' => ($usuario->getUbicacion()) ? $usuario->getUbicacion()->getId() : null,
                    'token' => $usuario->getToken(),
                    'identificador' => $usuario->getIdentificador(),
        ));
    }

    /**
     * 
     */
    public function deleteEntity(Usuario $usuario) {
        return $this->delete("USUARIO", $usuario->getId());
    }

    /**
     * 
     */
    public function updateEntity(Usuario $usuario) {
        return $this->update("USUARIO", array(
                    'usuario' => $usuario->getUsuario(),
                    'clave' => $usuario->getClave(),
                    'nombre' => $usuario->getNombre(),
                    'apellido' => $usuario->getApellido(),
                    'documento' => $usuario->getDocumento(),
                    'email' => $usuario->getEmail(),
                    'telefono' => $usuario->getTelefono(),
                    'identificador' => $usuario->getIdentificador(),
                    'token' => $usuario->getToken(),
                    'id_rol' => $usuario->getRol()->getId(),
                    'id_ubicacion' => ($usuario->getUbicacion()) ? $usuario->getUbicacion()->getId() : null,
                    'habilitado' => $usuario->getHabilitado() ? 1 : 0,
                    'bajaLogica' => $usuario->getBajaLogica() ? 1 : 0), $usuario->getId());
    }

    /**
     * Convierte un la Entidad de Arreglo a Clase
     * 
     * @param array $arrayEntity Arreglo de la Entidad
     * @return boolean False - Si no se encontró pudo recuperar las relaciones de la Entidad
     * @return Usuario Entidad convertida en Clase
     */
    private function getEntityByArray($arrayEntity) {
        $entity = new Usuario();
        $entity->setApellido($arrayEntity['apellido']);
        $entity->setBajaLogica(filter_var($arrayEntity['bajaLogica'], FILTER_VALIDATE_BOOLEAN, array('flags' => FILTER_NULL_ON_FAILURE)));
        $entity->setHabilitado(filter_var($arrayEntity['habilitado'], FILTER_VALIDATE_BOOLEAN, array('flags' => FILTER_NULL_ON_FAILURE)));
        $entity->setClave($arrayEntity['clave']);
        $entity->setDocumento($arrayEntity['documento']);
        $entity->setEmail($arrayEntity['email']);
        $entity->setId($arrayEntity['id']);
        $entity->setNombre($arrayEntity['nombre']);
        $entity->setTelefono($arrayEntity['telefono']);
        $entity->setToken($arrayEntity['token']);
        $entity->setIdentificador($arrayEntity['identificador']);
        $entity->setUsuario($arrayEntity['usuario']);
        if (!$rol = RolModelOrm::getInstance()->getById($arrayEntity['id_rol'])) {
            return FALSE;
        }
        $entity->setRol($rol);
        if ($arrayEntity['id_ubicacion'] != null) { //Por si no tiene ubicacion el usuario
            if (!$ubicacion = UbicacionModelOrm::getInstance()->getById($arrayEntity['id_ubicacion'])) {
                return FALSE;
            }
            $entity->setUbicacion($ubicacion);
        }

        return $entity;
    }

    /**
     * Obtiene el Usuario por el ID
     * 
     * @param integer $id Id del Usuario
     * @return boolean False si no encontró el Usuario
     * @return Usuario Usuario
     */
    public function getById($id) {
        $consulta = ""
                . "SELECT * "
                . "FROM USUARIO "
                . "WHERE id = :id";

        $result = $this->select($consulta, array(
            'id' => $id
        ));
        if (count($result) > 0) {
            $elemento = reset($result);
            return $this->getEntityByArray($elemento);
        } else {
            return FALSE;
        }
    }

    /**
     * Recupera el Usuario mediante sus datos de session (identificador + token)
     * 
     * @param string $identificador Identificador de session
     * @param string $token Token de session
     * @return boolean False - Si no encuentra al usuario.
     * @return Usuario Usuario encontrados
     */
    public function getBySession($identificador, $token) {
        $consulta = ""
                . "SELECT * "
                . "FROM USUARIO "
                . "WHERE bajaLogica = 0 AND habilitado = 1 AND token = :token AND identificador = :identificador ";
        $result = $this->select($consulta, array(
            'identificador' => $identificador,
            'token' => $token
        ));
        if (count($result) > 0) {
            $elemento = reset($result);
            return $this->getEntityByArray($elemento);
        } else {
            return FALSE;
        }
    }

    /**
     * Recupera el Usuario mediante sus datos de login (usuario + contraseña)
     * 
     * @param string $user Nombre de usuario
     * @param string $password Contrseña del usaurio
     * @return boolean False - Si no encuentra al usuario.
     * @return Usuario Usuario encontrado
     */
    public function getUsuario($user, $password) {
        $consulta = ""
                . "SELECT * "
                . "FROM USUARIO "
                . "WHERE bajaLogica = 0 AND habilitado = 1 AND clave = :password AND usuario  = :user ";
        $result = $this->select($consulta, array(
            'user' => $user,
            'password' => $password
        ));
        if (count($result) > 0) {
            $elemento = reset($result);
            return $this->getEntityByArray($elemento);
        } else {
            return FALSE;
        }
    }

    /**
     * Recupera todos los usuarios del sistema activos
     * 
     * @return array  De entidades Usuario almacenadas en la BD
     */
    public function getAll() {
        $consulta = ""
                . "SELECT * "
                . "FROM USUARIO AS u "
                . "WHERE u.bajaLogica = 0 ";
        $result = $this->select($consulta);
        $arreglo = array();
        foreach ($result as $usuario) {
            $arreglo[] = $this->getEntityByArray($usuario);
        }
        return $arreglo;
    }

    /**
     * Recupera todos los usuarios del sistema sin importar su estado
     * 
     * @return array  De entidades Usuario almacenadas en la BD
     */
    public function getAllAnything() {
        $consulta = ""
                . "SELECT * "
                . "FROM USUARIO ";
        $result = $this->select($consulta);
        $arreglo = array();
        foreach ($result as $usuario) {
            $arreglo[] = $this->getEntityByArray($usuario);
        }
        return $arreglo;
    }

    /**
     * Identifica si el nombre de usuario ya se encuentra cargado en la BD
     * 
     * @param string $usuario Usuario
     * @return boolean True si existe el usuario y False caso contrario
     */
    public function existUsuario($usuario) {
        $consulta = ""
                . "SELECT * "
                . "FROM USUARIO AS u "
                . "WHERE u.usuario = :usuario AND u.bajaLogica = 0";
        $result = $this->select($consulta, array(
            "usuario" => $usuario
        ));
        return (count($result) > 0) ? TRUE : FALSE;
    }

    /**
     * 
     */
    public function getUsuariosPag($nroPagina) {
        $result = $this->getGenericoTablaPaginado('USUARIO', $nroPagina, NULL, 'apellido');
        $arreglo = array();
        foreach ($result as $usuario) {
            $arreglo[] = $this->getEntityByArray($usuario);
        }
        return $arreglo;
    }

    /**
     * 
     */
    public function getUsuariosDesHabPag($nroPagina) {
        $result = $this->getGenericoTablaPaginado('USUARIO', $nroPagina, 'WHERE habilitado = 0', 'apellido');
        $arreglo = array();
        foreach ($result as $usuario) {
            $arreglo[] = $this->getEntityByArray($usuario);
        }
        return $arreglo;
    }

    /**
     * 
     */
    public function getCantPaginas() {
        return $this->getGenericoCantPaginas('USUARIO');
    }

    /**
     * 
     */
    public function getCantPaginasDesHab() {
        return $this->getGenericoCantPaginas('USUARIO', 'WHERE habilitado = 0');
    }

}
