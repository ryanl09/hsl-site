<?php

abstract class Controller {
    protected $model;

    public function render($page) {
        echo $_SERVER['DOCUMENT_ROOT'] . '/' . 'views/' . $page . '.php';
        include($_SERVER['DOCUMENT_ROOT'] . '/' . 'views/' . $page . '.php');
    }
}

?>