<?php

$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path . '/classes/util/ajaxerror.php');

require_once($path . '/classes/event/Schedule.php');
require_once($path . '/classes/general/Game.php');
require_once($path . '/classes/services/RegisterService.php');
require_once($path . '/classes/user/User.php');
require_once($path . '/classes/util/Sessions.php');
require_once($path . '/classes/util/tecdb.php');

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
            case 'schedule':
    
                if (!isset($_GET['teams'])) {
                    echo ajaxerror::e('errors', ['Missing teams']);
                    die();
                }
    
                if (!isset($_GET['days'])) {
                    echo ajaxerror::e('errors', ['Missing days']);
                    die();
                }
    
                if (!isset($_GET['times'])) {
                    echo ajaxerror::e('errors', ['Missing times']);
                    die();
                }
    
                if (!isset($_GET['weeks'])) {
                    echo ajaxerror::e('errors', ['Missing week count']);
                    die();
                }
    
                if (!isset($_GET['start_day'])) {
                    echo ajaxerror::e('errors', ['Missing start day']);
                    die();
                }
    
                $teams = $_GET['teams'];
                $days = $_GET['days'];
                $times = $_GET['times'];
                $weeks = intval($_GET['weeks']);
                $start_day = $_GET['start_day'];
    
                /*echo implode(',', $_GET['teams']) . PHP_EOL . implode(',', $_GET['days']) . PHP_EOL . implode(',', $_GET['times']) . PHP_EOL . $weeks . PHP_EOL . $start_day;
                die();*/
    
                $sch = Schedule::generate($start_day, $days, $times, $weeks, $teams);
    
                echo json_encode($sch);
                die();
                break;
            default:
                echo ajaxerror::e('errors', ['Invalid action']);
                die();
                break;
        }
    } else if ($_SERVER['REQUEST_METHOD']==='POST'){
        $action = $_POST['action'];

        if (isset($_POST['csrf'])) {
            if ($_POST['csrf']!==$_SESSION['csrf']){
                echo ajaxerror::e('errors',['Invalid CSRF token']);
                die();
            }
        } else {
            echo ajaxerror::e('errors',['Missing CSRF token']);
            die();
        }

        switch ($action){
            case 'upload':
                if (!isset($_POST['schedule'])) {
                    echo ajaxerror::e('errors', ['Missing schedule data']);
                    die();
                }
                if (!isset($_POST['game_id'])){
                    echo ajaxerror::e('errors', ['Missing game ID']);
                    die();
                }

                $failed=[];

                $db = new tecdb();

                $s = json_decode($_POST['schedule']);
                for ($i = 0; $i < count($s); $i++) {
                    if (isset($s[$i]->meta)) {
                        continue;
                    }

                    $m = $s[$i]->matches;
                    for ($j = 0; $j < count($m); $j++){
                        $date = date('Y-m-d', strtotime($s[$i]->date));
                        $time = date('H:i:s', strtotime($m->time));

                        $query ="INSERT INTO `events` (`event_home`, `event_away`, `event_stream`, `event_date`, `event_time`, `event_game`, `event_winner`)
                        VALUES (?, ?, \"\", ?, ?, ?, 0)";
                        $id = $db->query($query, $m['home'], $m['away'], $date, $time, $_POST['game_id'])->lastInsertId();
                        if (!$id) {
                            $failed[] = array(
                                'date' => $date,
                                'time' => $time,
                                'home' => $m['home'],
                                'away' => $m['away']
                            );
                        }
                    }
                }

                if (empty($failed)) {
                    echo json_encode(
                        array(
                            'success' => 'Schedule was uploaded successfully'
                        )
                    );
                    die();
                }

                echo json_encode(
                    array(
                        'errors' => ['Some matches failed to upload'],
                        'failed' => $failed
                    )
                );
                die();
                break;
            default:
                echo ajaxerror::e('errors', ['Invalid action']);
                die();
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