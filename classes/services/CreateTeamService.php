<?php

//TESTED

require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/team/Team.php');
require_once('CreateService.php');

class CreateTeamService extends CreateService {
    public function __construct() {
        parent::__construct();
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

        $name = $params['name'];
        $user_id = $params['user_id'];
        $logo = $params['logo'];
        $active = 0;
        $mascot = $params['mascot'];
        $primarycolor = $params['primarycolor'];
        $secondarycolor = $params['secondarycolor'];
        $schoolcode = $this->generate_schoolcode();
        
        $query =
        "INSERT INTO `teams` (team_name, `user_id`, team_logo, active, mascot, primarycolor, secondarycolor, schoolcode)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $id = $this->db->query($query, $name, $user_id, $logo, $active, $mascot, $primarycolor, $secondarycolor, $schoolcode)->lastInsertID();
        return new Team($id);
    }

    /**
     * Creates a unique schoolcode used for when the student registers to join this team
     * @return  string
     */

    private function generate_schoolcode() {
        $code = bin2hex(random_bytes(10));
        while ($this->schoolcode_exists($code)) {
            $code = bin2hex(random_bytes(10));
        }
        return $code;
    }

    /**
     * Checks if the schoolcode already exists
     * @param   string  $code
     * @return  boolean
     */

     private function schoolcode_exists($code) {
        if (!$this->db) {
            return false;
        }

        $query = 
        "SELECT *
        FROM `teams`
        WHERE schoolcode = ?";

        $rows = $this->db->query($query, $code)->numRows();
        return $rows;
     }
}

?>