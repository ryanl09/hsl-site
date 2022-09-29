<?php

require_once($path . '/controllers/Controller.php');
class EventsController extends Controller {
    public function __construct() {
        $this->render('events');
    }
}

?>