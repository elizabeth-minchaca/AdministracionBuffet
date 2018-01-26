<?php

abstract class PDORepository {

    protected function getConnection() {
        $u = DB_USER;
        $p = DB_PASS;
        $db = DB_NAME;
        $host = DB_HOST;
        $connection = new PDO("mysql:dbname=$db;host=$host", $u, $p, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . DB_CHAR));
        return $connection;
    }

    protected function releaseConnection($connection) {
        $connection = null;
    }

    protected function select($sql, $array = array(), $fetchMode = PDO::FETCH_ASSOC) {
        $connection = $this->getConnection();
        $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $sth = $connection->prepare($sql);
        foreach ($array as $key => $value) {
            $sth->bindValue("$key", $value);
        }
        $sth->execute();
        $result = $sth->fetchAll($fetchMode);
        $this->releaseConnection($connection);
        return $result;
    }

    protected function insert($table, $data) {
        ksort($data);
        $fieldNames = implode('`, `', array_keys($data));
        $filedValues = ':' . implode(', :', array_keys($data));
        $connection = $this->getConnection();
        $sth = $connection->prepare("INSERT INTO $table (`$fieldNames`) VALUES ($filedValues)");
        foreach ($data as $key => $value) {
            $sth->bindValue(":$key", $value);
        }
        if ($sth->execute()) {
            $id = $connection->lastInsertId();
            $this->releaseConnection($connection);
            return $id;
        } else {
            return 0;
        }
    }

    protected function update($table, $data, $id) {
        ksort($data);
        $columnas = array_keys($data);
        foreach ($columnas as $key => $value) {
            $columnas[$key] = $value . ' = :' . $value;
        }
        $str = implode(' , ', array_values($columnas));
        $connection = $this->getConnection();
        $sth = $connection->prepare("UPDATE $table SET $str WHERE id = :id");
        foreach ($data as $key => $value) {
            $sth->bindValue(":$key", $value);
        }
        $sth->bindValue(':id', $id);
        $result = $sth->execute();
        $this->releaseConnection($connection);
        return $result;
    }

    protected function delete($table, $id) {
        $connection = $this->getConnection();
        $sth = $connection->prepare("DELETE FROM $table WHERE id = :id");
        $sth->bindValue(':id', $id);
        $result = $sth->execute();
        $this->releaseConnection($connection);
        return $result;
    }

    /**
     * Devuelve los elementos de acuerdo a la condicion dentro del WHERE
     */
    protected function getGenericoTablaPaginado($tableName, $pagina, $where, $colOrden, $orden = 'ASC') {
        $tamanioPag = ConfiguracionModel::getInstance()->getConfiguracion()->getPaginadoNumero();
        $inicio = ($pagina - 1) * $tamanioPag;
        return $this->select(''
                        . 'SELECT * '
                        . 'FROM ' . $tableName . ' '
                        . ($where ? $where : ' ') . ' '
                        . 'ORDER BY ' . $colOrden . ' ' . $orden . ' '
                        . 'LIMIT :inicio, :tamanio', array(
                    'inicio' => $inicio,
                    'tamanio' => $tamanioPag
        ));
    }
	
    /**
     * Devuelve cantidad de elementos de acuerdo a la condicion dentro del WHERE
     */
    protected function getGenericoCantPaginas($tabla, $where = '') {
        $tamanioPag = ConfiguracionModel::getInstance()->getConfiguracion()->getPaginadoNumero();
        $result = $this->select(''
                . 'SELECT * '
                . 'FROM ' . $tabla . ' '
                . ($where ? $where : ''));
        return ceil(count($result) / $tamanioPag);
    }

    protected function getExistId($tabla, $id) {
        $connection = $this->getConnection();
        $res = $connection->prepare("SELECT COUNT(id) FROM " . $tabla . " WHERE id = :id");
        $res->bindValue(':id', $id);
        $res->execute();
        $this->releaseConnection($connection);
        $resultado = $res->fetch();
        return (int) $resultado[0] > 0 ? true : false;
    }

}
