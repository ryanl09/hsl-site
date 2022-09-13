<?php

require_once('TeamAbstract.php');
require_once('Team.php');

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/classes/general/Season.php');
require_once($path . '/classes/user/TeamManager.php');

class SubTeam extends TeamAbstract {
    public function __construct($id) {
        parent::__construct($id);
    }

    public function register($season) {
        if (!$season) {
            return 0;
        }

        if($season==='current'){
            $season=Season::get_current();
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

    public function get_parent_team() {
        if (!$this->id || !$this->db) {
            return 0;
        }

        $query = 
        "SELECT `team_id`
        FROM `subteams`
        WHERE `id` = ?";

        $team_id = $this->db->query($query, $this->id)->fetchArray();
        return new Team($team_id['team_id'] ?? 0);
    }

    /**
     * Gets url of parent team logo
     * @return  string
     */

    public function get_logo() {
        $team = $this->get_parent_team();

        if (!$team->get_id()) {
            return '';
        }

        return $team->get_logo();
    }

    /**
     * gets all players on this team for current season
     * @return  array
     */

    public function get_players() {
        if (!$this->id) {
            return [];
        }

        $c_s = Season::get_current();

        $query =
        "SELECT users.name, users.user_id
        FROM `users`
        INNER JOIN `subteams`
            ON subteams.id = ?
        INNER JOIN `player_seasons`
            ON player_seasons.user_id = users.user_id AND player_seasons.subteam_id = ? AND player_seasons.season_id = ?";

        $res = $this->db->query($query, $this->id, $this->id, $c_s)->fetchAll();
        return $res;
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

    public static function delete($st_id, $div, $game_id) {
        if (!$st_id || !$div || !$game_id) { //invalid values
            return false;
        }

        $db=new tecdb();
        if (!isset($_SESSION['user'])) { //not signed in
            return false;
        }

        $user = $_SESSION['user'];
        if (!in_array($user->get_role(), ['team_manager', 'admin'])){ //insufficient perms
            return false;
        }

        $tm = new TeamManager($user->get_id());
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
}

?>