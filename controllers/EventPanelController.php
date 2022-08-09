<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/controllers/Controller.php');

class EventPanelController extends Controller {
    public function __construct() {
        $this->render('eventpanel');
    }
}

?>