<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/classes/security/ISecurity.php');

class PasswordHash implements ISecurity {
    private $password;

    public function __construct($password) { 
        $this->password = $password;
    }

    public function create() {
        return password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function verify($hash) {
        return password_verify($this->password, $hash);
    }
}

?>