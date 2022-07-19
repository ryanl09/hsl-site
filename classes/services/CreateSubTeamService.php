<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/team/SubTeam.php');
require_once('CreateService.php');

class CreateSubTeamService extends CreateService {
    public function __construct() {
        parent::__construct();
    }

    /**
     * Creates a new subteam: team_id, division, game_id
     * @param   array   $params
     * @return  int|SubTeam
     */

    public function create($params) {
        if (!$params || empty($params)) {
            return 0;
        }

        $team_id = $params['team_id'];
        $div = $params['division'];
        $game_id = $params['game_id'];

        $query = 
        "INSERT INTO subteams (team_id, division, game_id)
        VALUES (?, ?, ?)";

        $id = $this->db->query($query, $team_id, $div, $game_id)->lastInsertID();
        return new SubTeam($id);
    }
}

?>