<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/classes/general/Season.php');
require_once($path . '/classes/util/tecdb.php');

class Game {
    private function __construct() { }

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

        $id = $db->query($query, $name)->fetchArray();
        return intval($id['id']);
    }

    /**
     * gets all games from the database
     * @return  array
     */

    public static function get_all() {
        $db = new tecdb();

        $query =
        "SELECT `id`, `game_name`, `url`
        FROM `games`";

        $res = $db->query($query)->fetchAll();
        return $res;
    }

    /**
     * Gets all teams associated with a certain game
     * @param   int $game_id
     * @param   int  $div
     * @return  array
     */

    public static function get_teams($game_id, $div) {
        if(!$game_id){
            return [];
        }

        $season_id = Season::get_current();

        $db = new tecdb();

        $query=
        "SELECT teams.team_name, teams.id AS team_id, subteams.id AS subteam_id, teams.slug
        FROM teams
        INNER JOIN subteams
            ON subteams.team_id = teams.id AND subteams.game_id = ?
        INNER JOIN subteam_seasons
            ON subteams.id = subteam_seasons.subteam_id AND subteam_seasons.season_id = ?
        WHERE subteams.division = ?";

        $res = $db->query($query, $game_id, $season_id, $div)->fetchAll();
        return $res;
    }
}

?>