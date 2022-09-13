<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/controllers/Controller.php');
require_once($path . '/models/ForgotModel.php');

class ForgotController extends Controller {
    public function __construct() {
        $this->model = new ForgotModel();
        $this->render('forgot');
    }
}

?>