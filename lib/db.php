<?php

$DB = new DB();
global $DB;

class DB {

    private $db;

    function __construct() {
        $this->db = $this->dbConnect();
    }

    public function dbConnect () {

        try {
            $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);

            return new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }

    }

    public function dbPFX($sql){
        return str_replace('pfx_', DB_PREFIX, $sql);
    }

    public function dbGetAll($sql, $array = array(), $fetchMode = PDO::FETCH_OBJ) {

        $stmt = $this->db->prepare($this->dbPFX($sql));

        foreach ($array as $key => $value) {
            if (is_int($value)) {
                $stmt->bindValue("$key", $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue("$key", $value);
            }
        }
        $stmt->execute();

        if (DEBUG) {
            $error_code = $stmt->errorInfo();
            if (isset($error_code[0]) and $error_code[0] == '00000'){
            } else {
                echo "\PDO::errorInfo():\n";
                print_r($stmt->errorInfo());
                die();
            }
        }

        if ($fetchMode === PDO::FETCH_CLASS) {
            return $stmt->fetchAll($fetchMode, $class);
        } else {
            return $stmt->fetchAll($fetchMode);
        }
    }

    public function dbGetOne($sql, $array = array(), $fetchMode = PDO::FETCH_OBJ, $class = ''){
        $data = $this->dbGetAll($sql, $array, $fetchMode, $class);
        return (isset($data[0])) ? $data[0] : null;
    }

    public function dbInsert($table, $data) {

        $data = (array) $data;
        $table = DB_PREFIX . $table;
        ksort($data);
        $fieldNames = implode(',', array_keys($data));
        $fieldValues = ':'.implode(', :', array_keys($data));
        $stmt = $this->db->prepare("INSERT INTO $table ($fieldNames) VALUES ($fieldValues)");
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();

        if (DEBUG) {
            $error_code = $stmt->errorInfo();
            if (isset($error_code[0]) and $error_code[0] == '00000'){
            } else {
                echo "\PDO::errorInfo():\n";
                print_r($stmt->errorInfo());
                die();
            }
        }

        return $this->db->lastInsertId();
    }

    public function dbUpdate($table, $data, $where) {

        $data = (array) $data;
        $table = DB_PREFIX . $table;
        ksort($data);
        $fieldDetails = null;
        foreach ($data as $key => $value) {
            $fieldDetails .= "$key = :field_$key,";
        }
        $fieldDetails = rtrim($fieldDetails, ',');
        $whereDetails = null;
        $i = 0;
        foreach ($where as $key => $value) {
            if ($i == 0) {
                $whereDetails .= "$key = :where_$key";
            } else {
                $whereDetails .= " AND $key = :where_$key";
            }
            $i++;
        }
        $whereDetails = ltrim($whereDetails, ' AND ');
        $stmt = $this->db->prepare("UPDATE $table SET $fieldDetails WHERE $whereDetails");
        foreach ($data as $key => $value) {
            $stmt->bindValue(":field_$key", $value);
        }
        foreach ($where as $key => $value) {
            $stmt->bindValue(":where_$key", $value);
        }
        $stmt->execute();

        if (DEBUG) {
            $error_code = $stmt->errorInfo();
            if (isset($error_code[0]) and $error_code[0] == '00000'){
            } else {
                echo "\PDO::errorInfo():\n";
                print_r($stmt->errorInfo());
                die();
            }
        }

        return $stmt->rowCount();
    }

    public function dbDelete($table, $where, $limit = 1) {
        global $DB;

        $table = DB_PREFIX . $table;
        ksort($where);
        $whereDetails = null;
        $i = 0;
        foreach ($where as $key => $value) {
            if ($i == 0) {
                $whereDetails .= "$key = :$key";
            } else {
                $whereDetails .= " AND $key = :$key";
            }
            $i++;
        }
        $whereDetails = ltrim($whereDetails, ' AND ');
        //if limit is a number use a limit on the query
        if (is_numeric($limit)) {
            $uselimit = "LIMIT $limit";
        }
        $stmt = $this->db->prepare("DELETE FROM $table WHERE $whereDetails $uselimit");
        foreach ($where as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->execute();

        if (DEBUG) {
            $error_code = $stmt->errorInfo();
            if (isset($error_code[0]) and $error_code[0] == '00000'){
            } else {
                echo "\PDO::errorInfo():\n";
                print_r($stmt->errorInfo());
                die();
            }
        }

        return $stmt->rowCount();
    }

}

