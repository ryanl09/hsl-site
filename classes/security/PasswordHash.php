<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/classes/security/ISecurity.php');

class PasswordHash implements ISecurity {
    private function __construct() { }

    public static function create($password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public static function verify($password, $hash) {
        return password_verify($password, $hash);
    }
}

?>