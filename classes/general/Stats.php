<?php

class Stats {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Gets stats for a user for specific game and season
     * @param   int $user_id
     * @param   int $season_id
     * @param   int $game_id
     * @return  int|array
     */

    public function get($user_id, $season_id, $game_id) {
        if (!$user_id || !$season_id || !$game_id) {
            return [];
        }

        $query=
        "SELECT stat_cols.id, stat_cols.name, stats.stat_value
        FROM `stat_cols`
        INNER JOIN `stats`
            ON stats.user_id = ? AND stats.stat_id = stat_cols.id
        INNER JOIN `events`
            ON events.id = stats.event_id
        WHERE events.event_season = ? AND events.event_game = ?";

        $res = $this->db->query($query, $user_id, $season_id, $game_id)->fetchAll();
        return $res;
    }

    /**
     * gets all cols for a game
     * @param   $game_id
     * @return  array
     */

    public function get_cols_game($game_id){
        if (!$game_id){
            return [];
        }

        $query=
        "SELECT `id`, `name`
        FROM `stat_cols`
        WHERE `game_id` = ?";

        $res = $this->db->query($query, $game_id)->fetchAll();
        return $res;
    }

    /**
     * Gets all stat columns for an event
     * @param   int $event_id
     * @return  array
     */

    public function get_cols($event_id) {
        if (!$event_id) {
            return [];
        }

        $query =
        "SELECT stat_cols.id, stat_cols.name
        FROM `stat_cols`
        INNER JOIN `events`
            ON events.id = ?
        WHERE stat_cols.game_id = events.event_game
        ORDER BY stat_cols.id ASC";

        $res = $this->db->query($query, $event_id)->fetchAll();
        return $res;
    }

    /**
     * Gets stats for each player for a team from a certain event
     * @param   int $event_id
     * @param   int $subteam_id
     * @return  array
     */

    public function get_stats($event_id, $subteam_id) {
        if (!$event_id || !$subteam_id) {
            return [];
        }

        $temp = "";
        $a = func_get_args();
        if (count($a) > 2){
            $temp = $a[2] ? "" : "temp_";
        }

        $query = 
        "SELECT stats.user_id, stats.stat_id, stats.stat_value
        FROM `stats`
        INNER JOIN `stat_cols`
            ON stat_cols.id = stats.stat_id
        WHERE stats.user_id IN (
            SELECT ".$temp."event_rosters.user_id
            FROM ".$temp."event_rosters
            INNER JOIN `player_seasons`
                ON player_seasons.user_id = ".$temp."event_rosters.user_id AND player_seasons.subteam_id = ? AND player_seasons.season_id = ?
            WHERE ".$temp."event_rosters.event_id = ?
            )
        AND stats.event_id = ?";

        $res = $this->db->query($query, $subteam_id, Season::get_current($this->db), $event_id, $event_id)->fetchAll();
        return $res;
    }

    /**
     * updates or inserts a new stat
     * @param   int $user_id
     * @param   int $event_id
     * @param   int $stat_id
     * @param   int $stat_value
     * @return  boolean
     */

    public function add($user_id, $event_id, $stat_id, $stat_value) {
        $query = 
        "INSERT INTO `stats` (`user_id`, `event_id`, `stat_id`, `stat_value`) 
        VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE `stat_value` = ?";

        $ret = $this->db->query($query, $user_id, $event_id, $stat_id, $stat_value, $stat_value)->lastInsertID();
        return $ret;
    }

    /**
     * stats page
     * @param   int $game
     * @param   int $div
     * @return  array
     */

    public function stats_page_any($game, $div){
        if (!$game || !$div){
            return [];
        }

        $query=
        "SELECT SUM(stats.stat_value) AS total, stat_cols.id, user_igns.ign, teams.team_name, users.username
        FROM stats
        INNER JOIN stat_cols
            ON stat_cols.id = stats.stat_id
        INNER JOIN user_igns
            ON user_igns.user_id = stats.user_id
        INNER JOIN users
            ON stats.user_id = users.user_id
        INNER JOIN teams
            ON users.team_id = teams.id
        WHERE stat_cols.game_id = ?
        GROUP BY stat_cols.id, user_igns.ign, users.username, teams.team_name
        ORDER BY user_igns.ign, stat_cols.id";

        $res = $this->db->query($query, $game)->fetchAll();
        $ret=array();

        $last_ign = '';
        $idx=0;
        foreach ($res as $i => $row){ //format stats
            if (strcmp($row['ign'], $last_ign)!==0){
                $idx = $this->index_of_name($ret, $row['ign']);
                if($idx<0){
                    $idx=count($ret);
                }
            }
            $ret[$idx]['stats'][]=array(
                'stat_id' => $row['id'],
                'stat_total' => $row['total']
            );
            $ret[$idx]['ign']=$row['ign'];
            $ret[$idx]['team']=$row['team_name'];
            $last_ign = $row['ign'];
            unset($res[$i]);
        }

        return $ret;
    }

    /**
     * temporary fix to format stats
     * @param   array   $arr
     * @param   string  $name
     * @return  int
     */

    private function index_of_name($arr, $name){
        $idx=-1;
        for ($i = 0; $i < count($arr); $i++){

            if (!isset($arr['ign'])){
                return $idx;
            }

            if (strcmp($arr['ign'], $name)===0){
                $idx = $i;
                break;
            }
        }
        return $idx;
    }

    /**
     * returns top players of the week
     * @param   int     $game
     * @param   int     $division
     * @param   int     $stat_id
     */
    public function get_top_players_of_week($game, $division, $stat_id) {
        $previous_week = strtotime("+1 day");
        $start_week = strtotime("last sunday midnight", $previous_week);
        $end_week = strtotime("next saturday", $start_week);
        $start_week = date("Y-m-d", $start_week);
        $end_week = date("Y-m-d", $end_week);

        /*echo "<script>console.log('";
        echo $start_week.' '.$end_week ;
        echo "');</script>";*/

        $query=
        "SELECT SUM(stats.stat_value) AS total, stat_cols.id, user_igns.ign, teams.team_name, users.username
        FROM stats
        INNER JOIN stat_cols
            ON stat_cols.id = stats.stat_id
        INNER JOIN user_igns
            ON user_igns.user_id = stats.user_id
        INNER JOIN users
            ON stats.user_id = users.user_id
        INNER JOIN teams
            ON users.team_id = teams.id
        INNER JOIN events
            ON stats.event_id = events.id
        WHERE stat_cols.game_id = ? AND stats.stat_id = ? AND events.event_date >= \"$start_week\"
        GROUP BY stat_cols.id, user_igns.ign, users.username, teams.team_name
        ORDER BY total DESC LIMIT 5";

        $res = $this->db->query($query, $game, $stat_id)->fetchAll();

        return $res;
    }
}

?>