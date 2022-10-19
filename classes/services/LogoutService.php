<?php

$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path . '/documentelements.php');

class LogoutService {
    public static function logout() {
        session_destroy();
        $_SESSION = array();

        $args = func_get_args();
        if(count($args)){
            if(!$args[0]){
                return;
            }
        }

        header('Location: ' . href('login'));
    }
}

?>