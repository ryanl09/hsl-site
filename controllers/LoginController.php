<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/controllers/Controller.php');
//require_once($path . '/models/LoginModel.php');

class LoginController extends Controller {
    public function __construct() {
        //$this->model = new LoginModel();
        $this->render('login');
    }
}

?>