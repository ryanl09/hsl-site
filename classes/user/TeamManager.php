<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once('User.php');
require_once($path . '/classes/general/Season.php');

class TeamManager extends User {
    public function __construct($db, $id) {
        parent::__construct($db, $id, 'team_manager');
    }

    public function get_team() {
        $team_id = 0;

        if (!$this->id || !$this->db) {
            return new Team($team_id);
        }

        $query =
        "SELECT `id`
        FROM `teams`
        WHERE `user_id` = ?";

        $res = $this->db->query($query, $this->id)->fetchArray();
        return $res['id'];
    }

    /**
     * gets all subteams of team manager
     * @return  array
     */

    public function get_subteams() {
        if (!$this->id) {
            return [];
        }

        $c_s = Season::get_current();

        $query=
        "SELECT subteams.id
        FROM subteams
        INNER JOIN subteam_seasons
            ON subteam_seasons.subteam_id = subteams.id AND subteam_seasons.season_id = ?
        INNER JOIN users
            ON subteams.team_id = users.team_id
        WHERE users.user_id = ?";

        $res = $this->db->query($query, $c_s, $this->id)->fetchAll();

        $st=[];
        foreach ($res as $i => $row){
            $st[] = $row['id'];
        }
        return $st;
    }
}

?>