<?php

class Db {
    protected $conn = false;
    protected $sql = '';

    public function __construct($sql = '') {
        $this->sql = $sql;
        $this->conn = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
//        if (mysqli_connect_errno()){}
    }

    public function query($sql) {
        return new Db($sql);
    }

    /**
     * @param $table
     * @param array $cols
     * @return Db
     */
    public function select($table, $cols = array()) {
        $sql = "SELECT ";
        if(empty($cols)) {
            $sql .= '* ';
        } else {
            foreach($cols as $col){
                $sql .= $col;
                end($cols) === $col ? $sql .= ' ' : $sql .= ', ';
            }
        }
        $sql .= 'FROM ' . $table;
        return new Db($sql);
    }

    /**
     * @param array $args
     * @return Db
     */
    public function where($args = array()) {
        $sql = $this->sql;
        if($args){
            $sql .= ' WHERE ';
            foreach ($args as $arg){
                $sql .= $arg;
                end($args) === $arg ? $sql .= ' ' : $sql .= ' AND ';
            }
        }

        return new Db($sql);
    }

    public function escape($var) {
        return $this->conn->real_escape_string($var);
    }

    public function exec($i = '') {
        $res = $this->conn->query($this->sql);

        if($res->num_rows > 0) {
            $return = [];
            $j=0;
            while ($row = $res->fetch_assoc()) {
                $return[] = $row;
                if($i != null && ++$j == $i){
                    break;
                }
            }
            if($i == 0)
                return $return[0];
            else
                return $return;
        } else {
            return null;
        }
    }

    public function dump() {
        return $this->sql;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function create($data = array()) {
        if($data['name'] && $data['cols']){
            if(!isset($data['engine'])) $data['engine'] = 'innodb';

            $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $data['name'] . "` (".PHP_EOL;

            $i = 0;
            foreach($data['cols'] as $column => $property){
                $sql .= "`" . $column  . "` " . $property;
                if($i++ == 0){ //first column is auto_incremented and public_key
                    $primary_key = $column;
                    $sql .= ' AUTO_INCREMENT';
                }
                $sql .= ','.PHP_EOL;
            }
            $sql .= ' PRIMARY KEY (`' . $primary_key . '`)) ENGINE='.$data['engine'].';';

            if(isset($data['debug']) && $data['debug'] === true) return $sql;

            return $this->conn->query($sql);
        } else {
            return false;
        }
    }

    public function __destruct() {
        $this->conn->close();
    }
}