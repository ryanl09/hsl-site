<?php

require_once('TeamAbstract.php');
require_once('Team.php');

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/classes/general/Season.php');
require_once($path . '/classes/user/User.php');
require_once($path . '/classes/user/TeamManager.php');

class SubTeam extends TeamAbstract {
    public function __construct($db, $id) {
        parent::__construct($db, $id);
    }

    public function register($season) {
        if (!$season) {
            return 0;
        }

        if($season==='current'){
            $season=Season::get_current($this->db);
        }

        $query = 
        "INSERT INTO `subteam_seasons`
        VALUES (?, ?)";

        $id = $this->db->query($query, $this->id, $season)->lastInsertID();
        return $id ?? 0;
    }

    /**
     * Gets id of parent team
     * @return  int
     */

    public function get_parent_team_id() {
        if (!$this->id || !$this->db) {
            return 0;
        }

        $query = 
        "SELECT `team_id`
        FROM `subteams`
        WHERE `id` = ?";

        $team_id = $this->db->query($query, $this->id)->fetchArray();
        return $team_id['team_id'] ?? 0;
    }

    /**
     * Gets url of parent team logo
     * @return  string
     */

    public function get_logo() {
        $team_id = $this->get_parent_team_id();

        if (!$team_id) {
            return '';
        }

        $team = new Team($this->db, $team_id);
        return $team->get_logo();
    }

    /**
     * gets all players on this team for current season
     * @param   boolean $temp_pl
     * @return  array
     */

    public function get_players($temp_pl) {
        if (!$this->id) {
            return [];
        }

        $temp = "WHERE `is_temp` = " . ($temp_pl ? '1' : '0');

        $c_s = Season::get_current($this->db);

        $query =
        "SELECT users.name, users.user_id, user_igns.ign, users.username
        FROM `users`
        INNER JOIN `subteams`
            ON subteams.id = ?
        INNER JOIN `player_seasons`
            ON player_seasons.user_id = users.user_id AND player_seasons.subteam_id = ? AND player_seasons.season_id = ?
        LEFT OUTER JOIN `user_igns`
            ON user_igns.user_id = users.user_id
        $temp";

        $res = $this->db->query($query, $this->id, $this->id, $c_s)->fetchAll();
        return $res;
    } 

    /**
     * adds a player to the subteam for current season
     * @param   int $pid
     * @return  boolean
     */

    public function add_player($pid) {
        if (!$pid){
            return false;
        }

        $player = new User($this->db, $pid);
        if (!session_id() || !isset($_SESSION['user'])){
            return false;
        }

        $user = $_SESSION['user'];
        if (!in_array($user->get_role(), ['team_manager', 'admin'])){
            return false;
        }

        $parent = $this->get_parent_team_id();
        if (!$user->is_admin()){
            if (($parent !== $user->get_team_id()) || $parent !== $player->get_team_id()){
                return false;
            }
        }

        $c_s = Season::get_current($this->db);

        $query=
        "INSERT INTO `player_seasons` (`user_id`, `subteam_id`, `season_id`)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE subteam_id = subteam_id";

        $res = $this->db->query($query, $pid, $this->id, $c_s);

        return $pid;
    }

    /**
     * static methods
     */

    /**
     * delete a team from tm dashboard
     * @param   int $st_id
     * @param   int $div
     * @param   int $game_id
     * @return  boolean
     */

    public static function delete($db, $st_id, $div, $game_id) {
        if (!$st_id || !$div || !$game_id) { //invalid values
            return false;
        }

        if (!isset($_SESSION['user'])) { //not signed in
            return false;
        }

        $user = $_SESSION['user'];
        if (!in_array($user->get_role(), ['team_manager', 'admin'])){ //insufficient perms
            return false;
        }

        $tm = new TeamManager($db, $user->get_id());
        $st = $tm->get_subteams();

        if (!in_array($st_id, $st)) { //user does not own this team
            return false;
        }

        $query=
        "DELETE FROM `subteams`
        WHERE `id` = ? AND `division` = ? AND `game_id` = ?";

        $res = $db->query($query, $st_id, $div, $game_id)->affectedRows();
        return $res;
    }

    /**
     * gets record of team
     * @param   int $team_id
     * @return  array
     */

    public static function get_record($db, $team_id){
        if (!$team_id){
            return [];
        }

        $query=
        "SELECT COUNT(*) as wins
        FROM events
        WHERE (event_home = ? AND event_winner = event_home) OR (event_away = ? AND event_winner = event_away)";

        $wins = $db->query($query, $team_id, $team_id)->fetchArray();

        $query=
        "SELECT COUNT(*) as losses
        FROM events
        WHERE ((event_home = ? AND event_winner <> event_home) OR (event_away = ? AND event_winner <> event_away)) AND event_winner <> 0";

        $losses = $db->query($query, $team_id, $team_id)->fetchArray();

        return array(
            'wins' => $wins['wins'],
            'losses' => $losses['losses']
        );
    }

    /**
     * get all records of teams
     * @param   array   $team_ids
     * @return  array
     */

    public static function get_records($db, $team_ids){
        if (empty($team_ids)){
            return [];
        }

        $res=[];
        for ($i = 0; $i < count($team_ids); $i++){
            $id = $team_ids[$i];
            if (isset($res[$id])){
                continue;
            }
            $res[$id] = self::get_record($db, $id);
        }

        return $res;
    }

    /**
     * sees if subteam exists
     * @param   int $pl_id
     * 
     */

    public static function exists($db, $pl_id, $div, $game){
        $query=
        "SELECT subteams.id
        FROM subteams
        INNER JOIN users
            ON users.user_id = ?
        WHERE subteams.team_id = users.team_id AND subteams.division = ? AND subteams.game_id = ?";

        $res = $db->query($query, $pl_id, $div, $game)->fetchArray();

        return !empty($res);
    }
}

?>