<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/controllers/Controller.php');
//require_once($path . '/models/RegisterModel.php');

class RegisterController extends Controller {
    public function __construct() {
        //$this->model = new RegisterModel();
        $this->render('register');
    }
}

?>