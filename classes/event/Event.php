<?php
$path = $_SERVER['DOCUMENT_ROOT'];

require_once('IEvent.php');
require_once($path . '/classes/general/Season.php');
require_once($path . '/classes/util/tecdb.php');

class Event implements IEvent {
    private $id;
    private $db;

    public function __construct($id, $tecdb) {
        $this->id = $id;
        $this->db = $tecdb;
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
        "SELECT events.event_home AS t_id, teams.team_logo AS logo, teams.team_name AS t_name, teams.slug, events.event_home_score AS score, subteams.tag
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
        "SELECT events.event_away AS t_id, teams.team_logo AS logo, teams.team_name AS t_name, teams.slug, events.event_away_score AS score, subteams.tag
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

    public static function exists($db, $event_id) {
        if (!$event_id) {
            return false;
        }
        $query =
        "SELECT EXISTS(
            SELECT * 
            FROM `events`
            WHERE `id` = ?) AS ex";
        $res = $db->query($query, $event_id)->fetchArray();
        $ret = false;
        if ($res['ex']) {
            $ret = new Event($event_id, $db);
        }
        return $ret;
    }

    /**
     * gets game img for event
     * @param   int $event_id
     * @return  string
     */

    public static function game_image($db, $event_id){
        if (!$event_id){
            return '';
        }

        $query=
        "SELECT games.url
        FROM games
        INNER JOIN events
            ON events.event_game = games.id
        WHERE events.id = ?";

        $res=$db->query($query, $event_id)->fetchArray();
        return $res['url'] ?? '';
    }

    /**
     * Gets all events for the current day
     * @return  array
     */

    public static function all_today($db) {
        $d = date('Y-m-d');
        $t = date('H:i:s');

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

    public static function all_today_game($db, $game_id) {
        if (!$game_id) {
            return [];
        }


        $d = date('Y-m-d');
        //$d = date("2022-10-04");

        $query=
        "SELECT t.team_name as event_home, t2.team_name as event_away, events.event_time, events.event_stream, s.division, s.id as h_id, s2.id as a_id, 
        events.event_winner, events.id as event_id, s.tag as home_tag, s2.tag as away_tag
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

    public static function get_players($db, $event_id, $h, $a) {
        if (!$event_id) {
            return [];
        }

        $c_s = Season::get_current($db);

        $h_h = self::has_roster($db, $event_id, $h);
        $h_p = self::get_roster($db, $event_id, $h, $h_h);

        $a_h = self::has_roster($db, $event_id, $a);
        $a_p = self::get_roster($db, $event_id, $a, $a_h);

        return array_merge($h_p, $a_p);
    }

    /**
     * sees if an event is going on now, if there is then send all the stuff   DOESNT WORK
     * @return  boolean|array
     */

    public static function is_now($db) {
        $d = date('Y-m-d');
        $t = date('H:i:s');
        $t2 = date('H:i:s', strtotime('+1 hour'));

        /** need to account different intervals for different games, 1 hour for now */

        $query=
        "SELECT t.team_name as event_home, t.team_logo as home_logo, t2.team_name as event_away, t2.team_logo as away_logo, 
        events.event_time, events.event_stream, s.division, s.tag as home_tag, s2.tag as away_tag
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

    public static function set_score($db, $event_id, $h, $a){
        if (!$event_id){
            return false;
        }

        $query = 
        "UPDATE `events`
        SET `event_home_score` = ?, `event_away_score` = ?
        WHERE `id` = ?";

        $res = $db->query($query, $h, $a, $event_id)->affectedRows();

        $query = 
        "SELECT `event_home`, `event_away`
        FROM `events`
        WHERE `id` = ?";
        $re = $db->query($query, $event_id)->fetchArray();
        $team = $h > $a ? $re['event_home'] : $re['event_away'];
        $set_win = self::set_winner($db, $event_id, $team);

        return $res > 0 && $set_win;
    }

    
    /**
     * sets winner
     * @param   int $event_id
     * @param   int $team_id
     * @return  boolean
     */

    public static function set_winner($db, $event_id, $team_id){
        if (!$event_id){
            return false;
        }

        $query=
        "UPDATE `events`
        SET `event_winner` = ?
        WHERE `id` = ?";

        $res = $db->query($query, $team_id, $event_id)->affectedRows();
        return $res > 0;
    }

    /**
     * gets next event that hasnt started yet
     * @return  array
     */

    public static function get_next($db) {
        $d = date('Y-m-d');
        $t = date('H:i:s');

        $query=
        "SELECT t.team_name as event_home, t.team_logo as home_logo, t2.team_name as event_away, t2.team_logo as away_logo, 
        events.event_date, events.event_time, events.event_stream, s.division, s.tag as home_tag, s2.tag as away_tag, games.url as game_logo
        FROM `events`
        INNER JOIN subteams s
            ON s.id = events.event_home
        INNER JOIN teams t
            ON s.team_id = t.id
        INNER JOIN subteams s2
            ON s2.id = events.event_away
        INNER JOIN teams t2
            ON s2.team_id = t2.id
        INNER JOIN games
            ON events.event_game = games.id
        WHERE events.event_date >= now() AND events.event_time >= ?
        ORDER BY events.event_date ASC, events.event_time, s.division ASC LIMIT 1";

        $res = $db->query($query, $t)->fetchArray();
        return empty($res) ? false : $res;
    }

    /**
     * sort by
     * @param   int $game
     * @param   int $team
     * @param   int $div
     * @return  array
     */

    public static function sort_by($db, $game, $team, $div, $time){
        if (!$team || !$div || !$time){
            return [];
        }

        $c_s = Season::get_current($db);

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

        $time_str = "";
        $today = date('Y-m-d'); //todays date
        $toda = date("H:i:s"); //time now
        if (strcmp($time, 'upcoming')===0){
            $time_str = "AND events.event_date >= \"$today\"";
        } else if (strcmp($time,'past')===0){
            $time_str = "AND events.event_date <= \"$today\"";
        }

        $where = $team_str . $div_str . $time_str;

        $query =
        "SELECT t.team_name as event_home, t.team_logo as home_logo, t2.team_name as event_away, t2.team_logo as away_logo, events.event_winner, events.event_date, 
        events.event_time, events.event_stream, s.division, events.id as event_id, events.event_home_score as home_score, events.event_away_score as away_score,
        s.tag as home_tag, s2.tag as away_tag
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

        $res = $db->query($query, $team, $team, $div, $game, $c_s)->fetchAll();
        return $res;
    }

    /**
     * gets events of team id
     * @param   int $sid
     * @return  array
     */

    public static function of_subteam($db, $sid) {
        if (!$sid){
            return [];
        }

        $c_s=Season::get_current($db);

        $query =
        "SELECT t.team_name as event_home, t.team_logo as home_logo, t2.team_name as event_away, t2.team_logo as away_logo, 
        events.event_date, events.event_time, events.event_away as a_id, events.id as e_id, s.tag as home_tag, s2.tag as away_tag
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

    public static function get_roster($db, $event_id, $team_id){
        if (!$event_id){
            return [];
        }

        $temp="";
        $args = func_get_args();
        if (count($args) > 3){
            $temp = $args[3] ? "" : "temp_";
        }

        $query=
        "SELECT ".$temp."event_rosters.user_id, users.name, ".$temp."event_rosters.subteam_id, user_igns.ign
        FROM ".$temp."event_rosters
        INNER JOIN users
            ON ".$temp."event_rosters.user_id = users.user_id
        INNER JOIN events
            ON events.id = ?
        INNER JOIN user_igns
            ON user_igns.user_id = users.user_id AND user_igns.game_id = events.event_game
        WHERE ".$temp."event_rosters.event_id = ? AND ".$temp."event_rosters.subteam_id = ?";

        $res = $db->query($query, $event_id, $event_id, $team_id)->fetchAll();
        return $res;
    }

    /**
     * sees if event has a roster
     * @param   int $event_id
     * @param   int $team_id
     * @return  array
     */

    public static function has_roster($db, $event_id, $team_id){
        if (!$event_id){
            return [];
        }
        $res = self::get_roster($db, $event_id, $team_id);
        return !empty($res);
    }
}

?>