<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/util/TECDB.php');

abstract class CreateService {
    protected $id;

    public function __construct() {
        $this->db = new TECDB();
    }

    public abstract function create($params);
}

?>