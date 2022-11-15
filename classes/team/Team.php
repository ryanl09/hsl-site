<?php

$path = $_SERVER['DOCUMENT_ROOT'];

require_once('SubTeam.php');
require_once('TeamAbstract.php');
require_once($path . '/classes/general/Season.php');
require_once($path . '/classes/services/CreateSubTeamService.php');

class Team extends TeamAbstract {
    public function __construct($db, $id) {
        parent::__construct($db, $id);
    }

    /**
     * Gets team name
     * @return  string
     */

    public function get_team_name() {
        if (!$this->id) {
            return '';
        }

        $query =
        'SELECT `team_name`
        FROM `teams`
        WHERE `id` = ?';

        $res = $this->db->query($query, $this->id)->fetchArray();
        return $res['team_name'];
    }

    /**
     * Gets a list of subteam ids
     * @return  array
     */

    public function get_subteams() {
        if (!$this->id) {
            return [];
        }

        $query = 
        "SELECT *
        FROM `subteams`
        WHERE `team_id` = ?";

        $subteams = $this->db->query($query, $this->id)->fetchArray();
        return $subteams;
    }

    /**
     * gets subteams WITH game name
     * @return  array
     */

    public function get_subteams_games() {
        if (!$this->id) {
            return [];
        }

        $query=
        "SELECT subteams.division, subteams.id, games.game_name, games.id AS game_id, games.url
        FROM `teams`
        INNER JOIN `subteams`
            ON subteams.team_id = teams.id
        INNER JOIN `games`
            ON subteams.game_id = games.id
        WHERE teams.id = ?
        ORDER BY games.game_name, subteams.division";
        $res = $this->db->query($query, $this->id)->fetchAll();
        return $res;
    }

    /**
     * Adds a subteam
     * @param   int $division
     * @param   int $game
     * @return  int|SubTeam
     */

    public function add_subteam($db, $division, $game) {
        if (!$this->id) {
            return 0;
        }

        $cst = new CreateSubTeamService($db);

        $params = array(
            'team_id' => $this->id,
            'division' => $division,
            'game_id' => $game,
        );

        $id = $cst->create($params);
        return new SubTeam($db, $id);
    }

    /**
     * Deletes a subteam
     * @param   int $subteam
     * @return  boolean
     */

    public function remove_subteam($subteam) {
        if (!$this->id) {
            return 0;
        }

        $query = 
        "DELETE FROM `subteams`
        WHERE `team_id` = ?
        AND `id` = ?";

        $rows = $this->db->query($query, $this->id, $subteam)->affectedRows();
        return count($rows) > 0;
    }

    /**
     * Deletes a team
     * @return  boolean
     */

    public function remove() {
        if (!$this->id) {
            return 0;
        }

        $query = 
        "DELETE FROM `teams`
        WHERE id = ?";

        $rows = $this->db->query($query, $this->id)->affectedRows();
        return count($rows) > 0;
    }

    /**
     * removes player from team
     * @param   int $pl_id
     * @return  boolean
     */

    public function remove_player($pl_id){
        if (!$pl_id){
            return false;
        }

        $query=
        "UPDATE `users`
        SET `team_id` = 0
        WHERE `user_id` = ?";
        $res1 = $this->db->query($query, $pl_id)->affectedRows();

        $query =
        "DELETE FROM `player_seasons`
        WHERE `user_id` = ?";
        $res = $this->db->query($query, $pl_id)->affectedRows();

        return $res1 > 0;

    }

    /**
     * Gets id of team
     * @return  int
     */

    public function get_id() {
        return $this->id;
    }

    /**
     * Registers a new team
     * @param   int $season
     * @return  int
     */

    public function register($season) {
        if (!$season) {
            return 0;
        }
        return 1;
    }

    /**
     * gets the schoolcode of a team
     * @return  string
     */

    public function get_schoolcode() {
        if (!$this->id){
            return '';
        }

        $query=
        "SELECT `schoolcode`
        FROM `teams`
        WHERE `id` = ?";
        $res = $this->db->query($query, $this->id)->fetchArray();
        return $res['schoolcode'];
    }

    /**
     * Gets url of team logo
     * @return  string
     */

    public function get_logo() {
        if (!$this->id) {
            return '';
        }

        $query =
        "SELECT `team_logo`
        FROM `teams`
        WHERE `id` = ?";

        $logo = $this->db->query($query, $this->id)->fetchArray();
        return $logo['team_logo'] ?? '';
    }

    /**
     * gets team manager for a team
     * @param   int $id
     * @return  int|array
     */

    public function get_team_manager(){
        if (!$this->id){
            return 0;
        }

        $query=
        "SELECT users.name, users.user_id
        FROM users
        INNER JOIN teams
            ON teams.id = users.team_id
        WHERE teams.id = ? LIMIT 1;";

        $tm = $this->db->query($query, $this->id)->fetchArray();
        return $tm;
    }

    /**
     * gets all players on team, regardless of game
     * @param   boolean $temp_pl
     * @return  array
     */

    public function get_players($temp_pl) {
        if (!$this->id){
            return [];
        }

        $temp = " AND `is_temp` = " . ($temp_pl ? '1 ' : '0 ');

        $query=
        "SELECT `user_id`, `username`, `name`
        FROM `users`
        WHERE `team_id` = ? AND `role` <> \"team_manager\"
            $temp
        ORDER BY `name` ASC";
        $res = $this->db->query($query, $this->id)->fetchAll();
        return $res;
    }

    /**
     * get seasons of team
     * @return  array
     */

    public function get_seasons(){
        if (!$this->id){
            return [];
        }

        $query=
        "SELECT DISTINCT subteam_seasons.season_id, seasons.season_name
        FROM `subteam_seasons`
        INNER JOIN `seasons`
            ON seasons.id = subteam_seasons.season_id
        INNER JOIN `subteams`
            ON subteams.id = subteam_seasons.subteam_id
        WHERE subteams.team_id = ?";

        $res = $this->db->query($query, $this->id)->fetchAll();
        return $res;
    }

    /**
     * static functions
     */
    

    /**
     * Gets total number of teams registered
     * @return  int
     */

    public static function count_total($db) {
        $query = 
        "SELECT COUNT(*)
        AS team_count
        FROM `teams`
        WHERE `active` = 0";

        $res = $db->query($query)->fetchArray();
        return intval($res['team_count']);
    }


     /**
      * Finds team id from school code
      * @param  string  $code
      * @return int
      */

    public static function from_schoolcode($db, $code) {
        if (!$code) {
            return 0;
        }

        $query =
        "SELECT `id`
        FROM `teams`
        WHERE `schoolcode` = ?";

        $res = $db->query($query, $code)->fetchArray();

        return $res['id'] ?? 0;
    }

    /**
     * gets team from slug / url
     * @param   $team_name
     * @return  Team|int
     */

    public static function from_slug($db, $team_name){
        if (!$team_name){
            return 0;
        }

        $query=
        "SELECT `id`
        FROM `teams`
        WHERE slug = ?";

        $res = $db->query($query, $team_name)->fetchArray();

        return $res['id'] ?? 0;
    }

    /**
     * gets all teams for hs division
     * @param   tecdb   $db
     * @return  array
     */

    public static function get_all_hs($db, $type){
        $c_s = Season::get_current($db);

        $query=
        "SELECT DISTINCT teams.id, teams.team_name, teams.team_logo, teams.slug, subteams.game_id, games.url
        FROM teams
        INNER JOIN subteams
            ON subteams.team_id = teams.id
        INNER JOIN subteam_seasons
            ON subteam_seasons.season_id = ? AND subteam_seasons.subteam_id = subteams.id
        INNER JOIN games
            ON games.id = subteams.game_id
        WHERE teams.id NOT IN (1, 2, 3, 24, 25, 26)
            AND teams.team_type = \"hs\"
        ORDER BY teams.team_name, subteams.game_id";
        $res = $db->query($query, $c_s)->fetchAll();
        return $res;
    }
}

?>