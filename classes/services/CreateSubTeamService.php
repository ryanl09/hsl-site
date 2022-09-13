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
        SELECT * FROM (SELECT ? as team_id, ? AS division, ? as game_id) as temp
        WHERE NOT EXISTS (
            SELECT team_id
            FROM subteams
            WHERE team_id = ? AND division = ? AND game_id = ?) LIMIT 1;";

        $id = $this->db->query($query, $team_id, $div, $game_id, $team_id, $div, $game_id)->lastInsertID();
        return $id;
    }
}

?>