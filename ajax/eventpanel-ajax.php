<?php

$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path . '/classes/util/ajaxerror.php');

require_once($path . '/classes/services/RegisterService.php');
require_once($path . '/classes/general/Game.php');
require_once($path . '/classes/user/User.php');
require_once($path . '/classes/util/Sessions.php');

if ((isset($_SERVER['HTTP_X_REQUESTED_WITH'])) && ($_SERVER['HTTP_X_REQUESTED_WITH']==='XMLHttpRequest')) {
    if ($_SERVER['REQUEST_METHOD']!=='GET') {
        echo ajaxerror::e('errors',['Bad request']);
        die();
    }

    if (!isset($_SESSION['user'])) {
        echo ajaxerror::e('errors',['Not signed in']);
        die();
    }

    $user = $_SESSION['user'];

    if (!$user->is_admin()) {
        echo ajaxerror::e('errors',['Invalid permissions']);
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
    
    if (isset($_GET['csrf'])) {
        if ($_GET['csrf']!==$_SESSION['csrf']){
            echo ajaxerror::e('errors',['Invalid CSRF token']);
            die();
        }
    } else {
        echo ajaxerror::e('errors',['Missing CSRF token']);
        die();
    }

    if (!isset($_GET['action'])) {
        echo ajaxerror::e('errors', ['Missing action']);
    }

    $action = $_GET['action'];
    switch ($action){
        case 'get_teams':
            if (!isset($_GET['game_id'])) {
                echo ajaxerror::e('errors', ['Missing game ID']);
                die();
            }

            $game_id=$_GET['game_id'];

            if (!isset($_GET['div'])){
                echo ajaxerror::e('errors',['Missing division']);
                die();
            }

            $div=$_GET['div'];

            $teams = Game::get_teams($game_id,$div);
            echo json_encode(array('teams' => $teams));
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