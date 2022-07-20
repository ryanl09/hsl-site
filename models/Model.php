<?php

$path = $_SERVER['DOCUMENT_ROOT'];

require_once('Model.php');
require_once($path . '/classes/util/tecdb.php');

abstract class Model {
    protected $db;

    public function __construct() {
        $this->db = new tecdb();
    }
}

?>