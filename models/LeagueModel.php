<?php

$path = $_SERVER['DOCUMENT_ROOT'];

require_once('Model.php');

class LeagueModel extends Model {
    public function __construct() {
        parent::__construct();
    }
}