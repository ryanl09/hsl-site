<?php

$path = $_SERVER['DOCUMENT_ROOT'];

require_once('SubTeam.php');
require_once('TeamAbstract.php');
require_once($path . '/classes/services/CreateSubTeamService.php');

class Team extends TeamAbstract {
    public function __construct($id) {
        parent::__construct($id);
    }

    /**
     * Gets team name
     * @return  string
     */

    public function get_team_name() {
        if (!$this->id || !$this->db) {
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
        if (!$this->id || !$this->db) {
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
        "SELECT subteams.division, subteams.id, games.game_name, games.id AS game_id
        FROM `teams`
        INNER JOIN `subteams`
            ON subteams.team_id = teams.id
        INNER JOIN `games`
            ON subteams.game_id = games.id
        WHERE teams.id = ?";
        $res = $this->db->query($query, $this->id)->fetchAll();
        return $res;
    }

    /**
     * Adds a subteam
     * @param   int $division
     * @param   int $game
     * @return  int|SubTeam
     */

    public function add_subteam($division, $game) {
        if (!$this->id || !$this->db) {
            return 0;
        }

        $cst = new CreateSubTeamService();

        $params = array(
            'team_id' => $this->id,
            'division' => $division,
            'game_id' => $game,
        );

        $id = $cst->create($params);
        return new SubTeam($id);
    }

    /**
     * Deletes a subteam
     * @param   int $subteam
     * @return  boolean
     */

    public function remove_subteam($subteam) {
        if (!$this->id || !$this->db) {
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
        if (!$this->id || $this->db) {
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
        if (!$pl_id || !$this->id){
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
     * Gets total number of teams registered
     * @return  int
     */

    public static function count_total() {
        $db = new tecdb();

        $query = 
        "SELECT COUNT(*)
        AS team_count
        FROM `teams`
        WHERE `active` = 0";

        $res = $db->query($query)->fetchArray();
        return intval($res['team_count']);
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
        if (!$this->id || !$this->db) {
            return '';
        }

        $team_id = $this->id;

        $query =
        "SELECT `team_logo`
        FROM `teams`
        WHERE `id` = ?";

        $logo = $this->db->query($query, $this->id)->fetchArray();
        return $logo['team_logo'] ?? '';
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
     * static functions
     */

     /**
      * Finds team id from school code
      * @param  string  $code
      * @return int
      */

    public static function from_schoolcode($code) {
        if (!$code) {
            return 0;
        }

        $db = new tecdb();

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

    public static function from_slug($team_name){
        if (!$team_name){
            return 0;
        }

        $db= new tecdb();
        $query=
        "SELECT `id`
        FROM `teams`
        WHERE slug = ?";

        $res = $db->query($query)->fetchArray();

        return $res['id'] ?? 0;
    }
}

?>