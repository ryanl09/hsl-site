<?php

$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path . '/classes/util/ajaxerror.php');

require_once($path . '/classes/event/Schedule.php');
require_once($path . '/classes/general/Game.php');
require_once($path . '/classes/util/Sessions.php');

if ((isset($_SERVER['HTTP_X_REQUESTED_WITH'])) && ($_SERVER['HTTP_X_REQUESTED_WITH']==='XMLHttpRequest')) {

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
    
    if ($_SERVER['REQUEST_METHOD']==='GET') {

        if (!isset($_GET['action'])) {
            echo ajaxerror::e('errors', ['Missing action']);
            die();
        }

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
                $res = Game::get_all();
                echo json_encode(array(
                        'games' => $res
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
} else {
    echo 'Access denied';
}

?>