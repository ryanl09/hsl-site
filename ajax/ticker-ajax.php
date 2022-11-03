<?php

include_once($path . '/classes/util/ajaxerror.php');
require_once($path . '/classes/event/Schedule.php');
require_once($path . '/classes/general/Game.php');
require_once($path . '/classes/team/SubTeam.php');
require_once($path . '/classes/util/Sessions.php');
    
if ($_SERVER['REQUEST_METHOD']==='GET') {
    $action = $_GET['action'];
    if (isset($_GET['csrf'])) {
        if ($_GET['csrf']!==$_SESSION['csrf']){
            echo ajaxerror::e('errors',['Invalid CSRF token']);
            die();
        }
    } else {
        echo ajaxerror::e('errors',['Missing CSRF token']);
        die();
    }

    switch ($action){
        case 'init':
            $res = Game::get_all($db);
            $events=[];
            $team_ids=[];
            $recs = [];

            unset($res[2]);
            unset($res[3]);

            foreach ($res as $i => $row){
                $r_id = $row['id'];
                $events[$r_id] = Game::get_events_week($db, $r_id);

                for ($j = 0; $j < count($events[$r_id]); $j++){
                    $team_ids[] = $events[$r_id][$j]['h_id'];
                    $team_ids[] = $events[$r_id][$j]['a_id'];
                }
            }

            $recs = SubTeam::get_records($db, $team_ids);

            echo json_encode(array(
                    'games' => $res,
                    'events' => $events,
                    'records' => $recs
                )
            );
            die();
            break;
        default:
            echo ajaxerror::e('errors', ['Invalid action']);
            die();
            break;
    }
} else if ($_SERVER['REQUEST_METHOD']==='POST'){
    $action = $_POST['action'];

    switch($action){
        default:
            break;
    }
} else {
    echo ajaxerror::e('errors',['Bad request']);
    die();
}

?>