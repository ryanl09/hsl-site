<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/controllers/Controller.php');

class TeamController extends Controller {
    public function __construct($team) {
        $this->render('team', $team);
    }
}

?>