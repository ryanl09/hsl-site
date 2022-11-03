<?php

//TESTED

$path = $_SERVER['DOCUMENT_ROOT'];

require_once($path . '/classes/team/Team.php');
require_once($path . '/classes/security/AuthToken.php');
require_once('CreateService.php');

class CreateTeamService extends CreateService {
    public function __construct($db) {
        parent::__construct($db);
    }

    /**
     * Creates a new team: name, user_id, logo, mascot, primarycolor, secondarycolor
     * @return int|Team
     */

     /*$cs = new CreateTeamService();

        $params = array(
            'name' => 'Ryan Team 2',
            'user_id' => 2,
            'logo' => 'ryan',
            'mascot' => 'Ryans 2',
            'primarycolor' => '#00ff00',
            'secondarycolor' => '#000000'
        );

        $team = $cs->create($params);*/

    public function create($params) {
        if (!$params || empty($params)) {
            return 0;
        }

        $at = new AuthToken(10);

        $name = $params['name'];
        $user_id = $params['user_id'];
        $logo = $params['logo'];
        $active = 1;
        $mascot = $params['mascot'];
        $primarycolor = $params['primarycolor'];
        $secondarycolor = $params['secondarycolor'];
        $schoolcode = $at->create();
        $slug = strtolower(str_replace(' ', '', $name));
        $ymca = $params['ymca'];
        
        $query =
        "INSERT INTO `teams` (team_name, `user_id`, team_logo, active, mascot, primarycolor, secondarycolor, schoolcode, slug, team_type)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $id = $this->db->query($query, $name, $user_id, $logo, $active, $mascot, $primarycolor, $secondarycolor, $schoolcode, $slug, $ymca)->lastInsertID();
        return $id;
    }
}

?>