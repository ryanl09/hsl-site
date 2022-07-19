<?php

require_once('CreateService.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/user/TeamManager.php');

class CreateTeamManagerService extends CreateService {
    public function __construct() {
        parent::__construct();
    }

    public function create($params) {

    }
}

?>