<?php

require_once('CreateService.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/user/Player.php');

class CreateCasterService extends CreateService {
    public function __construct($db) {
        parent::__construct($db);
    }

    public function create($params) {
        if (!$params || empty($params)) {
            return 0;
        }

        $id = $params['id'];
        $ign = $params['ign'];
        $name = $params['name'];
        $pronouns = $params['pronouns'];

    }
}

?>