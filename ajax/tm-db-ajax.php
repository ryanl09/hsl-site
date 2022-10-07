<?php

$path = $_SERVER['DOCUMENT_ROOT'];

require_once($path . '/classes/event/Event.php');
require_once($path . '/classes/security/csrf.php');
require_once($path . '/classes/services/CreateSubTeamService.php');
require_once($path . '/classes/team/SubTeam.php');
require_once($path . '/classes/team/Team.php');
require_once($path . '/classes/user/User.php');
include_once($path . '/classes/util/ajaxerror.php');
require_once($path . '/classes/util/Sessions.php');
require_once($path . '/classes/util/tecdb.php');

if ((isset($_SERVER['HTTP_X_REQUESTED_WITH'])) && ($_SERVER['HTTP_X_REQUESTED_WITH']==='XMLHttpRequest')) {
    if ($_SERVER['REQUEST_METHOD']==='POST') {
        $perms = ['admin', 'team_manager'];
    if (in_array($_SESSION['user'], $perms)) {
        echo ajaxerror::e('errors', ['Insufficient permissions']);
        die();
    }

    $csrf = CSRF::post();
    if (!$csrf){
        echo ajaxerror::e('errors', ['Invalid CSRF token']);
        die();
    }
    
    if (!isset($_POST['action'])) {
        echo json_encode(
            array(
                'error' => 'Missing action'
            )
        );
        die();
    }

    $action = $_POST['action'];

    switch ($action) {
        case 'save_teams':

            if (!isset($_POST['teams'])) {
                echo ajaxerror::e('errors', ['Missing teams']);
                die();
            }

            $teams = json_decode($_POST['teams'], true);
            $er = [];
            foreach ($teams as $i => $t) {
                $csts = new CreateSubTeamService();
                $id = $csts->create(
                    array(
                        'team_id' => $_SESSION['user']->get_team_id(),
                        'division' => $t['div'],
                        'game_id' => $t['game_id']
                    )
                );
                if($id){
                    $st = new SubTeam($id);
                    $reg = $st->register('current');
                    if (!$id){
                        $er[] = 'Can\'t insert: g_id=' . $t['game_id'] . ', d=' . $t['div'];
                    }
                } else {
                    $er[] = 'Team of g:' . $t['game_id'] . ' d:' . $t['div'] . ' already exists.';
                }
            }

            if(count($er)===count($teams)){
                echo ajaxerror::e('errors', $er);
            }else{
                echo json_encode(
                    array(
                        'status' => 1,
                        'success' => 'Teams updated'
                    )
                );
            }
            die();

            break;
        case 'remove':
            if (!isset($_POST['pl_id'])) {
                echo ajaxerror::e('errors', ['Missing player id']);
                die();
            }

            if (!isset($_SESSION['user'])){
                echo ajaxerror::e('errors', ['Missing user session']);
                die();
            }

            $pl = $_POST['pl_id'];
            $user = $_SESSION['user'];
            $t_id = $user->get_team_id();

            $team = new Team($t_id);
            $removed = $team->remove_player($pl);

            if ($removed){
                echo json_encode(
                    array(
                        'status' => 1,
                        'success' => 'Player removed'
                    )
                );
                die();
            }

            echo ajaxerror::e('errors', ['Could not remove this player']);
            die();
            break;
        case 'delete':
            if (!isset($_POST['st_id'])) {
                echo ajaxerror::e('errors', ['Missing team id']);
                die();
            }

            if (!isset($_POST['div'])) {
                echo ajaxerror::e('errors', ['Missing division']);
                die();
            }

            if (!isset($_POST['game_id'])) {
                echo ajaxerror::e('errors', ['Missing game id']);
                die();
            }

            $st_id = $_POST['st_id'];
            $div = $_POST['div'];
            $game_id = $_POST['game_id'];

            $del = SubTeam::delete($st_id, $div, $game_id);
            if ($del) {
                echo json_encode(
                    array(
                        'status' => 1,
                        'success' => 'Team successfully removed'
                    )
                );
                die();
            }
            echo ajaxerror::e('errors', ['Could not delete team']);
            die();
            break;
        case 'allocate':
            if (!isset($_POST['pl_id'])) {
                echo ajaxerror::e('errors', ['Missing player id']);
                die();
            }

            if (!isset($_POST['teams'])){ 
                echo ajaxerror::e('errors', ['Missing teams']);
                die();
            }

            if (!isset($_SESSION['user'])){
                echo ajaxerror::e('errors', ['Missing user session']);
                die();
            }

            $user = $_SESSION['user'];

            if (!in_array($user->get_role(), ['admin', 'team_manager'])){
                echo ajaxerror::e('errors', ['Insufficient user permission']);
                die();
            }

            $pl_id = $_POST['pl_id'];
            $teams=json_decode($_POST['teams']);

            $query = 
            "DELETE FROM `player_seasons`
            WHERE `user_id` = ?";

            $db = new tecdb();
            $db->query($query, $pl_id);

            foreach ($teams as $st) {
                $s = new SubTeam($st);
                $s->add_player($pl_id);
            }

            echo json_encode(
                array(
                    'status' => 1,
                    'success' => $pl_id
                )
            );
            die();

            break;
        case 'set_roster':
            if (!isset($_POST['e_id']) || !isset($_POST['players'])){
                echo ajaxerror::e('errors', ['Missing fields']);
                die();
            }

            $e_id = $_POST['e_id'];
            if (!is_numeric($e_id)){
                echo ajaxerror::e('errors', ['Invalid event']);
                die();
            }

            $pl = json_decode($_POST['players'], true);
            $query =
            "DELETE FROM `event_rosters`
            WHERE `event_id` = ?";
            $db = new tecdb();
            $r = $db->query($query, $e_id)->affectedRows();

            if (empty($pl)){
                echo json_encode(
                    array(
                        'status' => 1,
                        'success' => 'Roster cleared'
                    )
                );
                die();
            }

            $suc = 0;
            for ($i = 0; $i < count($pl); $i++){
                $query = 
                "INSERT INTO `event_rosters` (`user_id`, `event_id`)
                VALUES (?, ?);";

                $id = $db->query($query, $pl[$i], $e_id)->lastInsertID();
                $suc = ($id ? $id : 0);
            }

            if ($suc){
                echo json_encode(
                    array(
                        'status' => 1,
                        'success' => 'Roster set'
                    )
                );
                die();
            }

            echo ajaxerror::e('errors',  ['Couldn\'t set roster']);
            die();
            break;
        default:
            echo ajaxerror::e('errors', ['Invalid action']);
            die();
            break;
    }
    } else if ($_SERVER['REQUEST_METHOD']==='GET'){
        if (!isset($_GET['action'])){
            echo ajaxerror::e('errors', ['Missing action']);
            die();
        }

        $action = $_GET['action'];

        switch ($action){
            case 'get_teams':

                if (!isset($_GET['pl_id'])) {
                    echo ajaxerror::e('errors', ['Missing player']);
                    die();
                }

                $pl = $_GET['pl_id'];
                $user = new User($pl);

                echo json_encode($user->get_player_subteams());
                die();
                break;
            case 'get_players':
                if (!isset($_GET['st']) || !isset($_GET['e_id'])){
                    echo ajaxerror::e('errors', ['Missing some ids']);
                    die();
                }

                $e = $_GET['e_id'];
                $ros = Event::get_roster($e, $_GET['st']);
                $s = new Subteam($_GET['st']);
                echo json_encode(
                    array(
                        'status' => 1,
                        'players' => $s->get_players(false),
                        'roster' => $ros
                    )
                );
                die();
                break;
            case 'get_events':
                if (!isset($_GET['team'])){
                    echo ajaxerror::e('errors', ['Missing team']);
                    die();
                }

                $t = $_GET['team'];
                $e = Event::of_subteam($t);
                foreach ($e as $i => $row){
                    if (!isset($row['e_id'])){
                        continue;
                    }

                    $e[$i]['has_roster'] = Event::has_roster($row['e_id'], $t);
                }

                echo json_encode(
                    array(
                        'status' => 1,
                        'ev' => $e
                    )
                );
                die();
                break;
        }
    }else{
        echo 'Invalid request';
        die();
    }

    /*
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        $address = 'http://' . $_SERVER['SERVER_NAME'];
        if (strpos($address, $_SERVER['HTTP_ORIGIN']) !== 0) {
            echo json_encode(
                array(
                    'error' => 'Invalid origin header: ' . $_SERVER['HTTP_ORIGIN']
                );
            );
            die();
        }
    } else {
        echo json_encode(
            array(
                'error' => 'Missing origin header.'
            )
        );
        die();
    }
    */

    

} else {
    echo 'Access denied';
}

?>