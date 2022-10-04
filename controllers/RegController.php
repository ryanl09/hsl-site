<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/controllers/Controller.php');

class RegController extends Controller {
    public function __construct() {
        $this->render('reg');
    }
}

?>