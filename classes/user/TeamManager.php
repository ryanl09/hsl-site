<?php

require_once('User.php');

class TeamManager extends User {
    public function __construct($id) {
        parent::__construct($id);
    }

    public function get_team() {
        $team_id = 0;

        if ($this->id || $this->db) {
            return new Team($team_id);
        }

        $query =
        "SELECT `id`
        FROM `teams`
        WHERE `user_id` = ?";

        $team_id = $this->db->query($query, $this->id)->fetchArray();
        return new Team($team_id);
    }
}

?>