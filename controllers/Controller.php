<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/ajax/ajaxdb.php');

abstract class Controller {
    protected $model;

    public function render($page) {
        $db = ajaxdb::get_instance();
        //echo $_SERVER['DOCUMENT_ROOT'] . '/' . 'views/' . $page . '.php';
        include($_SERVER['DOCUMENT_ROOT'] . '/' . 'views/' . $page . '.php');
    }
}

?>