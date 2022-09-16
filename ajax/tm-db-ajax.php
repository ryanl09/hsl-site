<?php

$path = $_SERVER['DOCUMENT_ROOT'];

require_once($path . '/classes/security/csrf.php');
require_once($path . '/classes/services/CreateSubTeamService.php');
require_once($path . '/classes/team/SubTeam.php');
include_once($path . '/classes/util/ajaxerror.php');
require_once($path . '/classes/util/Sessions.php');

if ((isset($_SERVER['HTTP_X_REQUESTED_WITH'])) && ($_SERVER['HTTP_X_REQUESTED_WITH']==='XMLHttpRequest')) {
    if (!$_SERVER['REQUEST_METHOD']==='POST') {
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

    $perms = ['admin', 'team_manager'];
    if (in_array($_SESSION['user'], $perms)) {
        echo ajaxerror::e('errors', ['Insufficient permissions']);
        die();
    }

    $csrf = CSRF::post();
    if (!$csrf){
        echo ajaxerror::e('errors', ['Invalid CSRF token']);
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
        case 'delete':
            if (!isset($_POST['st_id'])) {
                echo ajaxerror::e('errors', ['Missing team id']);
            }

            if (!isset($_POST['div'])) {
                echo ajaxerror::e('errors', ['Missing division']);
            }

            if (!isset($_POST['game_id'])) {
                echo ajaxerror::e('errors', ['Missing game id']);
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

            $pl_id = $_POST['pl_id'];
            $teams=json_decode($_POST['teams']);

            foreach ($teams as $st) {
                $s = new SubTeam($st);
                $s->add_player($pl_id);
            }
            break;
        default:
            echo ajaxerror::e('errors', ['Invalid action']);
            die();
            break;
    }

} else {
    echo 'Access denied';
}

?>