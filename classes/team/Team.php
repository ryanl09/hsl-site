<?php

require_once('SubTeam.php');
require_once('TeamAbstract.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/services/CreateSubTeamService.php');

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
        WHERE `team_id` = ?';

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
        $db = new TECDB();

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

        $query = 
        "INSERT INTO `team_seasons` (team_id, season_id)
        VALUES (?, ?)";

        $id = $this->db->query($query, $this->id, $season)>lastInsertID();
        return $id;
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
        WHERE 'code' = ?";

        $res = $db->query($query, $code)->fetchArray();

        return $res['id'] ?? 0;
    }
}

?>