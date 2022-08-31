<?php

$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path . '/classes/event/Schedule.php');
include_once($path . '/classes/general/Game.php');
include_once($path . '/classes/general/Stats.php');

require_once($path . '/classes/event/Event.php');
require_once($path . '/classes/team/SubTeam.php');
require_once($path . '/classes/util/Sessions.php');

if ((isset($_SERVER['HTTP_X_REQUESTED_WITH'])) && ($_SERVER['HTTP_X_REQUESTED_WITH']==='XMLHttpRequest')) {
    if ($_SERVER['REQUEST_METHOD']==='POST') {
        
        if (isset($_POST['csrf'])) {
            if ($_POST['csrf']!==$_SESSION['csrf']){
                echo json_encode(
                    array(
                        'status' => 0,
                        'errors' => ['Invalid CSRF token']
                    )
                );
                die();
            }
        } else {
            echo json_encode(
                array(
                    'status' => 0,
                    'errors' => ['Missing CSRF token']
                )
            );
            die();
        }


        if (!isset($_POST['action'])) {
            echo ajaxerror::e('errors', ['Missing action']);
            die();
        }

        if (!isset($_POST['event_id'])) {
            echo ajaxerror::e('errors', ['Missing event id']);
            die();
        }

        $action = $_POST['action'];
    
        switch ($action){
            case 'stats':
                
                break;
        }
        

    } else if ($_SERVER['REQUEST_METHOD']==='GET'){

        if (isset($_GET['csrf'])) {
            if ($_GET['csrf']!==$_SESSION['csrf']){
                echo json_encode(
                    array(
                        'status' => 0,
                        'errors' => ['Invalid CSRF token']
                    )
                );
                die();
            }
        } else {
            echo json_encode(
                array(
                    'status' => 0,
                    'errors' => ['Missing CSRF token']
                )
            );
            die();
        }

        if (!isset($_GET['action'])) {
            echo ajaxerror::e('errors', ['Missing action']);
            die();
        }

        if (!isset($_GET['event_id'])){
            echo ajaxerror::e('errors', ['Missing event id']);
            die();
        }
        $event_id = intval($_GET['event_id']);

        $action=$_GET['action'];
        switch($action){
            case 'stats':
                $e = Event::exists($event_id);
                if (!$e){
                    echo ajaxerror::e('errors', ['Event does not exist']);
                    die();
                }
                $stats = new Stats();

                $home = $e->get_home_team();
                $away = $e->get_away_team();

                $ret = array(
                    'img' => array(
                        'width' => '220',
                        'height' => '220'
                    ),
                    'cols' => $stats->get_cols($event_id),
                    'home' => array(
                        'name' => $home['team_name'],
                        'logo' => $home['team_logo'],
                        'stats' => $stats->get_stats($event_id, $home['event_home'])
                    ),
                    'away' => array(
                        'name' => $away['team_name'],
                        'logo' => $away['team_logo'],
                        'stats' => $stats->get_stats($event_id, $away['event_away'])
                    )
                );

                echo json_encode($ret);
                die();
                break;
        }


    } else {
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