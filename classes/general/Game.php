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
        $db = new tecdb();

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
        "SELECT teams.team_name, teams.id AS team_id, subteams.id AS subteam_id, teams.slug, subteams.tag
        FROM teams
        INNER JOIN subteams
            ON subteams.team_id = teams.id AND subteams.game_id = ?
        INNER JOIN subteam_seasons
            ON subteams.id = subteam_seasons.subteam_id AND subteam_seasons.season_id = ?
        WHERE subteams.division = ?";

        $res = $db->query($query, $game_id, $season_id, $div)->fetchAll();
        return $res;
    }
    /**
     * Gets all teams associated with a certain game
     * @param   int $game_id
     * @return  array
     */

    public static function get_teams_g($game_id) {
        if(!$game_id){
            return [];
        }

        $season_id = Season::get_current();

        $db = new tecdb();

        $query=
        "SELECT teams.team_name, teams.id AS team_id, subteams.id AS subteam_id, teams.slug, subteams.tag
        FROM teams
        INNER JOIN subteams
            ON subteams.team_id = teams.id AND subteams.game_id = ?
        INNER JOIN subteam_seasons
            ON subteams.id = subteam_seasons.subteam_id AND subteam_seasons.season_id = ?
        ORDER BY teams.team_name ASC";

        $res = $db->query($query, $game_id, $season_id)->fetchAll();
        return $res;
    }

    /**
     * gets all events for a game this week
     * @param   int $game_id
     * @return  array
     */

    public static function get_events_week($game_id){
        if (!$game_id){
            return [];
        }

        $mon = date( 'Y-m-d', strtotime( 'monday this week' ) );
        $fri = date( 'Y-m-d', strtotime( 'friday this week' ) );

        $db = new tecdb();
        
        $query=
        "SELECT t.team_name AS home, t.team_logo as home_logo, t2.team_name AS away, t2.team_logo as away_logo, 
        events.event_time, events.event_date, s.division, events.id, s.tag as home_tag, s2.tag as away_tag,
        s.id as h_id, s2.id as a_id, events.event_home_score as h_score, events.event_away_score as a_score, events.event_winner
        FROM events
        INNER JOIN subteams s
            ON s.id = events.event_home
        INNER JOIN teams t
            ON s.team_id = t.id
        INNER JOIN subteams s2
            ON s2.id = events.event_away
        INNER JOIN teams t2
            ON s2.team_id = t2.id
        WHERE events.event_date >= ? AND events.event_date <= ? AND events.event_game = ?
        ORDER BY s.division ASC, events.event_time ASC";

        $res = $db->query($query, $mon, $fri, $game_id)->fetchAll();
        return $res;
    }

    /**
     * get events last week
     * @param   int $game_id
     * @return  array
     */

    public static function get_events_last_week($game_id){
        if (!$game_id){
            return [];
        }

        $mon = date( 'Y-m-d', strtotime( 'monday last week' ) );
        $fri = date( 'Y-m-d', strtotime( 'friday last week' ) );

        $db = new tecdb();

        $query=
        "SELECT t.team_name AS home, t.team_logo as home_logo, t2.team_name AS away, t2.team_logo as away_logo, 
        events.event_time, events.event_date, s.division, events.id, s.tag as home_tag, s2.tag as away_tag,
        s.id as h_id, s2.id as a_id
        FROM events
        INNER JOIN subteams s
            ON s.id = events.event_home
        INNER JOIN teams t
            ON s.team_id = t.id
        INNER JOIN subteams s2
            ON s2.id = events.event_away
        INNER JOIN teams t2
            ON s2.team_id = t2.id
        WHERE events.event_date >= ? AND events.event_date <= ? AND events.event_game = ?
        ORDER BY s.division ASC, events.event_time ASC";

        $res = $db->query($query, $mon, $fri, $game_id)->fetchAll();
        return $res;
    }
}

?>