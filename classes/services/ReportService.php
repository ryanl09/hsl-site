<?php

abstract class ReportService {
    protected $db;
    private $table;

    public function __construct($db){
        $this->db = $db;
    }

    abstract function insert($params);
}

?>