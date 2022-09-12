<?php

$path = $_SERVER['DOCUMENT_ROOT'];

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
    
    if (isset($_POST['csrf'])) {
        if ($_POST['csrf']!==$_SESSION['csrf']){
            echo json_encode(
                array(
                    'error' => 'Invalid CSRF token'
                )
            );
            die();
        }
    } else {
        echo json_encode(
            array(
                'error' => 'Missing CSRF token'
            )
        );
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
                $st = $csts->create(
                    array(
                        'team_id' => $_SESSION['user']->get_team_id(),
                        'division' => $t['div'],
                        'game_id' => $t['game_id']
                    )
                );
                if($st){
                    $id = $st->register('current');
                    if (!$id){
                        $er[] = 'Can\'t insert: g_id=' . $t['game_id'] . ', d=' . $t['div'];
                    }
                }
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