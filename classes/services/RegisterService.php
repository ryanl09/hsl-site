<?php

require_once('CreatePlayerService.php');
require_once('CreateTeamManagerService.php');


class RegisterService {
    private $type;

    public function __construct($type) {
        $this->type = $type;
    }

    /**
     * Registers a user based on what type they are
     * @param   array   $params
     * @return  int|Player|TeamManager|Caster|Admin|College
     */

    public function register($params) {
        $success = false;
        $cs = 0;
        switch ($type) {
            case 'player':
                $cs = new CreatePlayerService();
                break;
            case 'team_manager':
                $cs = new CreateTeamManagerService();
                break;
            case 'caster':
                $cs = new CreateCasterService();
                break;
            case 'admin':
                $cs = new CreateAdminService();
                break;
            case 'staff':
                //?
                break;
            case 'college':
                $cs = new CreateCollegeService();
                break;
        }

        if (!$cs){
            return 0;
        }

        $user = $cs->create();
        return $user;
    }

    private function _register_user() {
        /*wp register code*/
    }
}

?>