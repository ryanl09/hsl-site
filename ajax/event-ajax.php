<?php

$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path . '/classes/event/Schedule.php');
include_once($path . '/classes/general/Game.php');
include_once($path . '/classes/general/Stats.php');

require_once($path . '/classes/event/Event.php');
require_once($path . '/classes/team/SubTeam.php');
include_once($path . '/classes/util/ajaxerror.php');
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

        if (!isset($_POST['event_id'])){
            echo ajaxerror::e('errors', ['Missing event id']);
            die();
        }
        $event_id = intval($_POST['event_id']);
        if (!is_numeric($event_id)) {
            echo ajaxerror::e('errors', ['Invalid event id']);
        }

        $action = $_POST['action'];
    
        switch ($action){
            case 'stats':
                if (!$_SESSION['user']->is_admin()) {
                    echo ajaxerror::e('errors', ['Insufficient user permissions']);
                    die();
                }

                if (!isset($_POST['data'])) {
                    echo ajaxerror::e('errors', ['Missing data']);
                    die();
                }
                $obj = json_decode($_POST['data'], true);

                $stats = new Stats();

                $errors=[];

                foreach ($obj as $i => $row) {
                    $user_id = $row['u'];
                    $stat_id = $row['s'];
                    $stat_value = $row['v'];
                    if (!is_numeric($stat_value)) {
                        $errors[] = 'Entry ' . $i . ' error (Value was NAN)';
                        continue;
                    }
                    $stats->add($user_id, $event_id, $stat_id, $stat_value);
                }

                $pref=count($errors)<1?'All ':'Some ';
                echo json_encode(
                    array(
                        'status' => 1,
                        'success' => $pref . 'stats were entered',
                        'errors' => $errors
                    )
                );
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
                $h_stats = $stats->get_stats($event_id, $home['t_id']);
                $a_stats = $stats->get_stats($event_id, $away['t_id']);

                $ret = array(
                    'img' => array(
                        'width' => '220',
                        'height' => '220'
                    ),
                    'cols' => $stats->get_cols($event_id),
                    'home' => $home,
                    'away' => $away,
                    'stats' => array_merge($h_stats, $a_stats),
                    'players' => Event::get_players($event_id),
                );

                include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/user/User.php');
                if ($_SESSION['user']->is_admin()) {
                    $ret['p'] = 1;
                }

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