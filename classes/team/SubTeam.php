<?php

require_once('TeamAbstract.php');
require_once('Team.php');

class SubTeam extends TeamAbstract {
    public function __construct($id) {
        parent::__construct($id);
    }

    public function register($season) {
        if (!$season) {
            return 0;
        }

        $query = 
        "INSERT INTO `team_seasons` (team_id, season_id)
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

        if (!$team_id) {
            return '';
        }

        return $team->get_logo();
    }
}

?>