<?php
$path = $_SERVER['DOCUMENT_ROOT'];

require_once('IEvent.php');
require_once($path . '/classes/general/Season.php');
require_once($path . '/classes/util/tecdb.php');

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
        "SELECT events.event_home AS t_id, teams.team_logo AS logo, teams.team_name AS t_name, teams.slug, events.event_home_score AS score
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
        "SELECT events.event_away AS t_id, teams.team_logo AS logo, teams.team_name AS t_name, teams.slug, events.event_away_score AS score
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
     * gets event game id
     * @return  int
     */

    public function get_game_id(){
        if (!$this->id){
            return 0;
        }

        $query=
        "SELECT event_game
        FROM events
        WHERE id = ?";
        $res = $this->db->query($query, $this->id)->fetchArray();

        return $res['event_game'] ?? 0;
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
     * gets game img for event
     * @param   int $event_id
     * @return  string
     */

    public static function game_image($event_id){
        if (!$event_id){
            return '';
        }

        $query=
        "SELECT games.url
        FROM games
        INNER JOIN events
            ON events.event_game = games.id
        WHERE events.id = ?";

        $db = new tecdb();

        $res=$db->query($query, $event_id)->fetchArray();
        return $res['url'] ?? '';
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
        //$d = date("2022-10-04");

        $db = new tecdb();
        $query=
        "SELECT t.team_name as event_home, t2.team_name as event_away, events.event_time, events.event_stream, s.division, s.id as h_id, s2.id as a_id, events.event_winner, events.id as event_id
        FROM `events`
        INNER JOIN subteams s
            ON s.id = events.event_home
        INNER JOIN teams t
            ON s.team_id = t.id
        INNER JOIN subteams s2
            ON s2.id = events.event_away
        INNER JOIN teams t2
            ON s2.team_id = t2.id
        WHERE `event_date` = ? AND `event_game` = ?
        ORDER BY s.division ASC";

        $res = $db->query($query, $d, $game_id)->fetchAll();
        return $res;
    }

    /**
     * gets plays from a certain event
     * @param   int $event_id
     * @return  array
     */

    public static function get_players($event_id, $h, $a) {
        if (!$event_id) {
            return [];
        }

        $db = new tecdb();
        $c_s = Season::get_current();

        $h_h = self::has_roster($event_id, $h);
        $h_p = self::get_roster($event_id, $h, $h_h);

        $a_h = self::has_roster($event_id, $a);
        $a_p = self::get_roster($event_id, $a, $a_h);

        return array_merge($h_p, $a_p);
    }

    /**
     * sees if an event is going on now, if there is then send all the stuff   DOESNT WORK
     * @return  boolean|array
     */

    public static function is_now() {
        $d = date('Y-m-d');
        $t = date('H:i:s');
        $t2 = date('H:i:s', strtotime('+1 hour'));

        /** need to account different intervals for different games, 1 hour for now */

        $db = new tecdb();
        $query=
        "SELECT t.team_name as event_home, t.team_logo as home_logo, t2.team_name as event_away, t2.team_logo as away_logo, events.event_time, events.event_stream, s.division
        FROM `events`
        INNER JOIN subteams s
            ON s.id = events.event_home
        INNER JOIN teams t
            ON s.team_id = t.id
        INNER JOIN subteams s2
            ON s2.id = events.event_away
        INNER JOIN teams t2
            ON s2.team_id = t2.id
        WHERE events.event_date = ? AND events.event_time >= ? AND events.event_time < ? LIMIT 1";

        $res = $db->query($query, $d, $t, $t2)->fetchArray();
        return empty($res) ? false : $res;
    }

    /**
     * update score for event
     * @param   int $event_id
     * @param   int $home_score
     * @param   int $away_score
     * @return  boolean
     */

    public static function set_score($event_id, $h, $a){
        if (!$event_id){
            return false;
        }

        $db = new tecdb();
        $query = 
        "UPDATE `events`
        SET `home_score` = ?, `away_score` = ?
        WHERE `event_id` = ?";

        $res = $db->query($query, $h, $a, $event_id)->affectedRows();
        return $res > 0;
    }

    /**
     * gets next event that hasnt started yet
     * @return  array
     */

    public static function get_next() {
        $d = date('Y-m-d');
        $t = date('H:i:s');

        $db = new tecdb();
        $query=
        "SELECT t.team_name as event_home, t.team_logo as home_logo, t2.team_name as event_away, t2.team_logo as away_logo, events.event_date, events.event_time, events.event_stream, s.division
        FROM `events`
        INNER JOIN subteams s
            ON s.id = events.event_home
        INNER JOIN teams t
            ON s.team_id = t.id
        INNER JOIN subteams s2
            ON s2.id = events.event_away
        INNER JOIN teams t2
            ON s2.team_id = t2.id
        WHERE events.event_date >= now()
        ORDER BY events.event_date ASC, s.division ASC LIMIT 1";

        $res = $db->query($query)->fetchArray();
        return empty($res) ? false : $res;
    }

    /**
     * sort by
     * @param   int $game
     * @param   int $team
     * @param   int $div
     * @return  array
     */

    public static function sort_by($game, $team, $div){
        if (!$team || !$div){
            return [];
        }

        $c_s = Season::get_current();

        $team=intval($team);
        $div=intval($div);

        $team_str = "events.event_home = ? OR events.event_away = ? ";

        if ($team===-1){
            $team_str = "events.event_home <> ? AND events.event_away <> ? ";
        }

        $div_str = "AND s.division = ? ";

        if ($div===-1){
            $div_str = "AND s.division <> ? ";
        }

        $where = $team_str . $div_str;

        $query =
        "SELECT t.team_name as event_home, t.team_logo as home_logo, t2.team_name as event_away, t2.team_logo as away_logo, events.event_winner, events.event_date, events.event_time, events.event_stream, s.division, events.id as event_id
        FROM `events`
        INNER JOIN subteams s
            ON s.id = events.event_home
        INNER JOIN teams t
            ON s.team_id = t.id
        INNER JOIN subteams s2
            ON s2.id = events.event_away
        INNER JOIN teams t2
            ON s2.team_id = t2.id
        WHERE $where AND events.event_game=? AND events.event_season = ?
        ORDER BY events.event_date ASC, s.division ASC";

        $db =new tecdb();
        $res = $db->query($query, $team, $team, $div, $game, $c_s)->fetchAll();
        return $res;
    }

    /**
     * gets events of team id
     * @param   int $sid
     * @return  array
     */

    public static function of_subteam($sid) {
        if (!$sid){
            return [];
        }

        $c_s=Season::get_current();
        $db = new tecdb();

        $query =
        "SELECT t.team_name as event_home, t.team_logo as home_logo, t2.team_name as event_away, t2.team_logo as away_logo, events.event_date, events.event_time, events.event_away as a_id, events.id as e_id
        FROM `events`
        INNER JOIN subteams s
            ON s.id = events.event_home
        INNER JOIN teams t
            ON s.team_id = t.id
        INNER JOIN subteams s2
            ON s2.id = events.event_away
        INNER JOIN teams t2
            ON s2.team_id = t2.id
        WHERE events.event_season = ? AND (events.event_home = ? OR events.event_away = ?)
        ORDER BY events.event_date ASC";

        $res = $db->query($query, $c_s, $sid, $sid)->fetchAll();
        return $res;
    }

    /**
     * gets roster for event
     * @param   int $event_id
     * @param   int $team_id
     * @return  array
     */

    public static function get_roster($event_id, $team_id){
        if (!$event_id){
            return [];
        }

        $temp="";
        $args = func_get_args();
        if (count($args) > 2){
            $temp = $args[2] ? "" : "temp_";
        }

        $query=
        "SELECT ".$temp."event_rosters.user_id, users.name
        FROM ".$temp."event_rosters
        INNER JOIN users
            ON ".$temp."event_rosters.user_id = users.user_id
        INNER JOIN teams
            ON users.team_id = teams.id
        INNER JOIN subteams
            ON subteams.team_id = teams.id
        WHERE ".$temp."event_rosters.event_id = ? AND subteams.id = ?";

        $db=new tecdb();
        $res = $db->query($query, $event_id, $team_id)->fetchAll();
        return $res;
    }

    /**
     * sees if event has a roster
     * @param   int $event_id
     * @param   int $team_id
     * @return  array
     */

    public static function has_roster($event_id, $team_id){
        if (!$event_id){
            return [];
        }
        $res = self::get_roster($event_id, $team_id);
        return !empty($res);
    }
}

?>