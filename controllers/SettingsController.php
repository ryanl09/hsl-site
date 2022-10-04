<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/controllers/Controller.php');

class SettingsController extends Controller {
    public function __construct() {
        $this->render('settings');
    }
}

?>