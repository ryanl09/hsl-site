<?php

class CSRF {
    private function __construct() {}

    public static function get() {
        $valid = false;
        if (isset($_GET['csrf'])){
            if ($_GET['csrf'] === $_SESSION['csrf']){
                $valid=true;
            }
        }
        return $valid;
    }

    public static function post() {
        $valid = false;
        if (isset($_POST['csrf'])){
            if ($_POST['csrf']===$_SESSION['csrf']){
                $valid=true;
            }
        }
        return $valid;
    }
}

?>