<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/classes/util/tecdb.php');

class ajaxdb {
    private static $db;

    private function __construct() { }

    public static function get_instance(){
        if (!self::$db){
            self::$db = new tecdb();
        }

        return self::$db;
    }
}

?>