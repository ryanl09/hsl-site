<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/classes/security/ISecurity.php');

class AuthToken implements ISecurity {
    const DEFAULT_LENGTH = 25;

    private $length;

    public function __construct($length = 0) { 
        if (!$length) {
            $length = self::DEFAULT_LENGTH;
        }
        $this->length = $length;
    }

    /**
     * Creates an auth token of specified length (user registration, etc)
     * @return  string
     */

    public function create() {
        $bytes = random_bytes($this->length);
        return bin2hex($bytes);
    }
}

?>