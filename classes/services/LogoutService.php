<?php

$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path . '/documentelements.php');

class LogoutService {
    public static function logout() {
        session_destroy();
        $_SESSION = array();

        header('Location: ' . href('login'));
    }
}

?>