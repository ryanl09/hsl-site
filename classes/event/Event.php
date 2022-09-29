<?php

require_once('IEvent.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/util/tecdb.php');

class Event implements IEvent {
    private $id;
    private $db;

    public function __construct($id) {
        $this->id = $id;
        $this->db = new tecdb();
    }

    /**
     * Gets the id and logo of event's home team
     * @return  array
     */

    public function get_home_team() {
        if (!$this->id || !$this->db) {
            return [];
        }

        $query =
        "SELECT events.event_home AS t_id, teams.team_logo AS logo, teams.team_name AS t_name
        FROM `events`
        INNER JOIN `subteams`
            ON subteams.id = events.event_home
        INNER JOIN `teams`
            ON teams.id = subteams.team_id
        WHERE events.id = ?";

        $res = $this->db->query($query, $this->id)->fetchArray();
        return $res;
    }

    /**
     * Gets the id and logo of event's away team
     * @return  int
     */

    public function get_away_team() {
        if (!$this->id || !$this->db) {
            return [];
        }

        $query =
        "SELECT events.event_away AS t_id, teams.team_logo AS logo, teams.team_name AS t_name
        FROM `events`
        INNER JOIN `subteams`
            ON subteams.id = events.event_away
        INNER JOIN `teams`
            ON teams.id = subteams.team_id
        WHERE events.id = ?";

        $res = $this->db->query($query, $this->id)->fetchArray();
        return $res;
    }

    /**
     * Gets event id
     * @return  int
     */

    public function get_id() {
        return $this->id;
    }

    /**
     * Gets event date
     * @return  string
     */
    
    public function get_date() {
        if (!$this->id || !$this->db) {
            return 0;
        }

        $query =
        "SELECT `event_date`
        FROM `events`
        WHERE id = ?";

        $home_team = $this->db->query($query, $this->id)->fetchArray();
        return $home_team['event_date'];
    }

    /**
     * Updates event's date
     * @param   string  $date
     */

    public function set_date($date) {
        if (!$this->id || !$this->db || !$date) {
            return 0;
        }

        $query = 
        "UPDATE `events`
        SET `event_date` = ?
        WHERE id = ?";

        $row = $this->db->query($query, $date, $this->id)->affectedRows();
        return $row;
    }

    /**
     * Gets event time
     * @return  string
     */

    public function get_time() {
        if (!$this->id || !$this->db) {
            return 0;
        }

        $query =
        "SELECT `event_time`
        FROM `events`
        WHERE id = ?";

        $home_team = $this->db->query($query, $this->id)->fetchArray();
        return $home_team['event_time'];
    }

    /**
     * Updates event's time
     * @param   string  $time
     */

    public function set_time($time) {
        if (!$this->id || !$this->db || !$time) {
            return 0;
        }

        $query = 
        "UPDATE `events`
        SET `event_time` = ?
        WHERE id = ?";

        $row = $this->db->query($query, $time, $this->id)->affectedRows();
        return $row;

    }

    /**
     * Gets id of winning team
     */

    public function get_winner() {
        if (!$this->id || !$this->db) {
            return 0;
        }

        $query =
        "SELECT `event_winner`
        FROM `events`
        WHERE id = ?";

        $home_team = $this->db->query($query, $this->id)->fetchArray();
        return $home_team['event_time'];
    }

    /**
     * Gets id of losing team
     */

    public function get_loser() {
        if (!$this->id || !$this->db) {
            return 0;
        }

        
    }

    /**
     * static functions
     */



    /**
     * Check if event with this id exists
     * @return  boolean|Event
     */

    public static function exists($event_id) {
        if (!$event_id) {
            return false;
        }

        $db = new tecdb();

        $query =
        "SELECT EXISTS(
            SELECT * 
            FROM `events`
            WHERE `id` = ?) AS ex";
        $res = $db->query($query, $event_id)->fetchArray();
        $ret = false;
        if ($res['ex']) {
            $ret = new Event($event_id);
        }
        return $ret;
    }

    /**
     * Gets all events for the current day
     * @return  array
     */

    public static function all_today() {
        $d = date('Y-m-d');
        $t = date('H:i:s');

        $db = new tecdb();
        $query=
        "SELECT *
        FROM `events`
        WHERE `event_date` = ? AND `event_time` >= ?
        LIMIT 3";

        $res = $db->query($query, $d, $t)->fetchAll();
        return $res;
    }

    /**
     * Gets all events for the current day for a certain game
     * @param   int $game_id
     * @return  array
     */

    public static function all_today_game($game_id) {
        if (!$game_id) {
            return [];
        }


        $d = date('Y-m-d');

        $db = new tecdb();
        $query=
        "SELECT *
        FROM `events`
        WHERE `event_date` = ? AND `event_game` = ?";

        $res = $db->query($query, $d, $game_id)->fetchAll();
        return $res;
    }

    /**
     * gets plays from a certain event
     * @param   int $event_id
     * @return  array
     */

    public static function get_players($event_id) {
        if (!$event_id) {
            return [];
        }

        $db = new tecdb();
        $c_s = Season::get_current();

        $query =
        "SELECT users.name, users.user_id, player_seasons.subteam_id
        FROM `users`
        INNER JOIN `events`
            ON events.id = ?
        INNER JOIN `player_seasons`
            ON player_seasons.user_id = users.user_id AND player_seasons.season_id = ?
        WHERE (player_seasons.subteam_id = events.event_home OR player_seasons.subteam_id = events.event_away)";

        $res = $db->query($query, $event_id, $c_s)->fetchAll();
        return $res;
    }
}

?>