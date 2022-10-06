<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/classes/util/tecdb.php');

class ValidateAuth {
    private function __construct() { }

    /**
     * Validates a request to the broadcast api
     * @param   string  $user
     * @param   string  $token
     * @return  boolean
     */

    public static function broadcast($user, $token){
        $db = new tecdb();

        if (!$user || !$token){
            return false;
        }

        $sql =
        "SELECT `id`
        FROM `broadcast_auth`
        WHERE `user` = ? AND `token` = ?";

        $res = $db->query($sql, $user, $token)->numRows();
        return $res > 0;
    }
}

?>