<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/util/tecdb.php');

abstract class CreateService {
    protected $id;

    public function __construct() {
        $this->db = new tecdb();
    }

    public abstract function create($params);
}

?>