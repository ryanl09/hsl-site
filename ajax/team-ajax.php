<?php

require_once($path . '/classes/team/SubTeam.php');
require_once($path . '/classes/team/Team.php');

$action = $_GET['action'];

switch ($action){
    case 'get_info_tab':

        if (!isset($_GET['team'])){
            echo ajaxerror::e('errors', ['Team not set']);
            die();
        }
        $team=$_GET['team'];
        $t = Team::from_slug($db, $_GET['team']);
        $team = new Team($db, $t);

        $arr = array(
            'seasons' => $team->get_seasons(),
            'games' => $team->get_subteams_games()
        );

        echo json_encode(
            array(
                'status'=>1,
                'data'=> $arr
            )
        );
        break;
    case 'get_players':
        if (!isset($_GET['st_id'])){
            echo ajaxerror::e('errors', ['Missing subteam id']);
            die();
        }

        $id = $_GET['st_id'];
        $s = new SubTeam($db, $id);
        echo json_encode(
            array(
                'status'=>1,
                'players'=>$s->get_players(false)
            )
        );
        break;
    case 'get_stats_tab':
        echo json_encode(
            array(
                'status' => 1
            )
        );
        break;
    case 'get_highlights_tab':
        echo json_encode(
            array(
                'status' => 1
            )
        );
        break;
}

?>