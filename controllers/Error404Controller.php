<?php

require_once($path . '/controllers/Controller.php');
class Error404Controller extends Controller {
    public function __construct() {
        $this->render('error404');
    }
}

?>