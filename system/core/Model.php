<?php

class Model {
    protected $db;
    protected $table;
    protected $fields = array();

    public function __construct(){
//        $this->db = new Db('');
        $this->db = new PDO_DB();
    }

}