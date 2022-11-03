<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/util/tecdb.php');

abstract class CreateService {
    protected $id;

    public function __construct($db) {
        $this->db = $db;
    }

    public abstract function create($params);
}

?>