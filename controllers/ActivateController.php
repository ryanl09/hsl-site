<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/controllers/Controller.php');

class ActivateController extends Controller {
    public function __construct($auth_token) {
        $this->render('activate', $auth_token);
    }
}

?>