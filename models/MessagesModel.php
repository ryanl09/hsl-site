<?php

$path = $_SERVER['DOCUMENT_ROOT'];

require_once('Model.php');

class MessagesModel extends Model {
    public function __construct() {
        parent::__construct();
    }
}