<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/controllers/Controller.php');
require_once($path . '/models/DashboardModel.php');

class DashboardController extends Controller {
    public function __construct() {
        $this->model = new DashboardModel();
        $this->render('dashboard');
    }
}

?>