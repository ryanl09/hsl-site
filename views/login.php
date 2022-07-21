<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/classes/services/LoginService.php');
require_once($path . '/classes/user/User.php');
require_once($path . '/classes/util/Sessions.php');


if (isset($_POST['submitbtn'])) {
    $ls = new LoginService($_POST);
    $login = $ls->login();
    $status = $login['status'];

    if (!$status) {
        echo json_encode($login['errors']);
        die();
    }

    $user_id = $login['user_id'];
    $user = new User($user_id);

    if (session_id()) {
        session_regenerate_id();
    }
}



?>