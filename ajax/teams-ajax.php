<?php

require_once($path . '/classes/team/Team.php');

$action = $_GET['action'];

switch($action){
    case 'get_teams':
        if (!isset($_GET['type'])){
            echo ajaxerror::e('errors', ['No team type selected']);
            die();
        }

        $type = $_GET['type'];
        $teams = Team::get_all_hs($db, $type);

        echo json_encode(
            array(
                'status' => 1,
                'teams' => $teams
            )
        );

        break;
}

?>