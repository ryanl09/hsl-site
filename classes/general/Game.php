<?php

class Game {
    private function __construct() {

    }

    /**
     * Gets a game id by the name
     * @param   string  $name
     * @return  int
     */

    public static function by_name($name) {
        if (!$name) {
            return 0;
        }

        $name = strtolower($name);
        $db = new TECDB();

        $query =
        "SELECT `id`
        FROM `games`
        WHERE LOWER(`game_name`) = ?";

        $id = $db->query->($query, $name)->fetchArray();
        return intval($id['id']);
    }
}

?>