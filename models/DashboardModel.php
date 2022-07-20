<?php

$path = $_SERVER['DOCUMENT_ROOT'];

require_once($path . '/models/Model.php');

class DashboardModel extends Model {
    public function __construct() {
        parent::__construct();
    }
}

?>