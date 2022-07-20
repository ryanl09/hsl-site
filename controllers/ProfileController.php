<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/classes/user/User.php');
require_once($path . '/controllers/Controller.php');
require_once($path . '/models/ProfileModel.php');

class ProfileController extends Controller {
    public function __construct() {
        $this->model = new ProfileModel();
        $this->render('profile');
    }
}

?>