<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/util/TECDB.php');

class Season {
    private function __construct() {}

    /**
     * Gets current season id based on current date
     * @return  int
     */

    public static function get_current() {
        $db = new TECDB();
        $today = getdate();
        
        //august
        $name = $today['mon'] >= 8 ? 'Fall ' : 'Spring ' . $today['year'];

        $query =
        "SELECT `id`
        FROM `seasons`
        WHERE `season_name` = ?";

        $id = $db->query($query, $name)->fetchArray();
        return intval($id['id']);
    }

    /**
     * Adds a new season
     * @param   string  $name
     * @return  int
     */

    public static function add($name) {
        $db = new TECDB();

        $query = 
        "INSERT INTO `seasons` (season_name)
        VALUES (?)";

        $id = $db->query($query, $name)->lastInsertID();
        return $id;
    }
}

?>