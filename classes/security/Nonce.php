<?php

class Nonce {
    private function __construct() { }

    /**
     * creates a nonce and sets it to the session
     * @return  string
     */

    public static function generate() {
        if (!session_id()){
            return false;
        }

        $nonce = md5(uniqid(rand(), true));
        $_SESSION['nonce'] = $nonce;
        return $nonce;
    }

    /**
     * verifies value to session nonce
     * @param   string  $nonce
     * @return  boolean
     */

    public static function verify($nonce) {
        if (!session_id() || !isset($_SESSION['nonce'])) {
            return false;
        }

        return $_SESSION['nonce'] === $nonce;
    }

    /**
     * remove from the session
     */

    public static function destroy() {
        if (!session_id()){
            return;
        }

        if (isset($_SESSION['nonce'])) {
            unset($_SESSION['nonce']);
        }
    }
}

?>