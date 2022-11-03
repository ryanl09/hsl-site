<?php

include_once($path . '/classes/event/Event.php');
include_once($path . '/classes/event/Schedule.php');
include_once($path . '/classes/general/Game.php');
include_once($path . '/classes/general/Stats.php');

require_once($path . '/classes/event/Event.php');
require_once($path . '/classes/team/SubTeam.php');

    if ($_SERVER['REQUEST_METHOD']==='POST') {

        if (!isset($_POST['event_id'])) {
            echo ajaxerror::e('errors', ['Missing event id']);
            die();
        }
        $event_id = intval($_POST['event_id']);
        if (!is_numeric($event_id)) {
            echo ajaxerror::e('errors', ['Invalid event id']);
        }

        $action = $_POST['action'];
    
        switch ($action){
            case 'add_flag':
                if (!(isset($_SESSION['user']) && $_SESSION['user']->is_admin())) {
                    echo ajaxerror::e('errors', ['Insufficient user permissions']);
                    die();
                }
                if (!isset($_POST['flag_type']) || !isset($_POST['flag_reason'])){
                    echo ajaxerror::e('errors', ['Missing fields']);
                    die();
                }

                $flagtype = $_POST['flag_type'];
                $flagreason = $_POST['flag_reason'];

                $query = 'INSERT INTO event_flags
                            (event_id, flag_type, flag_reason)
                            VALUES (?, ?, ?)';
                $res = $db->query($query, $event_id, $flagtype, $flagreason)->lastInsertID();
                $suc = ($res ? $res : 0);

                if ($suc){
                    echo json_encode(
                        array(
                            'status' => 1,
                            'success' => 'Event flag added to current event'
                        )
                    );
                    die();
                }

                echo ajaxerror::e('errors',  ['Couldn\'t add event flag']);
                die();
                break;
            case 'stats':
                if (!(isset($_SESSION['user']) && $_SESSION['user']->is_admin())) {
                    echo ajaxerror::e('errors', ['Insufficient user permissions']);
                    die();
                }

                if (!isset($_POST['data']) || !isset($_POST['home_score']) || !isset($_POST['away_score'])) {
                    echo ajaxerror::e('errors', ['Missing data']);
                    die();
                }
                $obj = json_decode($_POST['data'], true);

                $home_score = $_POST['home_score'];
                $away_score = $_POST['away_score'];

                $stats = new Stats($db);

                $errors=[];

                $score_set = Event::set_score($db, $event_id, $home_score, $away_score);

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
                        'set_score' => $score_set,
                        'errors' => $errors
                    )
                );
                break;
            case 'add_roster':
                if (!isset($_SESSION['user']) || !isset($_POST['pl_id']) || !isset($_POST['team_id'])){
                    echo ajaxerror::e('errors', ['Missing fields']);
                    die();
                }

                if (!$_SESSION['user']->is_admin()){
                    echo ajaxerror::e('errors', ['Invalid permissions']);
                    die();
                }
                $team_id = $_POST['team_id'];

                $pl_id = $_POST['pl_id'];

                $query =
                "SELECT EXISTS(
                    SELECT `id`
                    FROM `temp_event_rosters`
                    WHERE `user_id` = ? AND `event_id` = ? AND `subteam_id` = ?
                ) AS ex";
                $res = $db->query($query, $pl_id, $event_id, $team_id)->fetchArray();

                if ($res['ex']) {
                    echo ajaxerror::e('errors', ['Player is already on the roster']);
                    die();
                }

                $suc = 0;
                
                $query = 
                "INSERT INTO `temp_event_rosters` (`user_id`, `event_id`, `subteam_id`)
                VALUES (?, ?, ?);";

                $id = $db->query($query, $pl_id, $event_id, $team_id)->lastInsertID();
                $suc = ($id ? $id : 0);

                if ($suc){
                    echo json_encode(
                        array(
                            'status' => 1,
                            'success' => 'Player added to roster'
                        )
                    );
                    die();
                }

                echo ajaxerror::e('errors',  ['Couldn\'t set roster']);
                die();
                break;
            case 'remove_roster':
                if (!isset($_SESSION['user']) || !isset($_POST['pl_id'])){
                    echo ajaxerror::e('errors', ['Missing fields']);
                    die();
                }

                if (!$_SESSION['user']->is_admin()){
                    echo ajaxerror::e('errors', ['Invalid permissions']);
                    die();
                }

                $pl_id = $_POST['pl_id'];
                $query = 
                "DELETE FROM `temp_event_rosters`
                WHERE `user_id` = ? AND `event_id` = ?";

                $res = $db->query($query, $pl_id, $event_id)->affectedRows();
                if ($res > 0){
                    echo json_encode(
                        array(
                            'status' => 1,
                            'success'=>'Player removed from roster'
                        )
                    );
                    die();
                }

                echo ajaxerror::e('errors', ['Couldn\'t remove player from roster']);
                die();

                break;
            default:
                echo ajaxerror::e('errors', ['Invalid action']);
                die();
                break;
        }
        

    } else if ($_SERVER['REQUEST_METHOD']==='GET'){
        if (!isset($_GET['event_id'])){
            echo ajaxerror::e('errors', ['Missing event id']);
            die();
        }
        $event_id = intval($_GET['event_id']);

        $action=$_GET['action'];
        switch($action){
            case 'stats':
                
                $e = Event::exists($db, $event_id);
                if (!$e){
                    echo ajaxerror::e('errors', ['Event does not exist']);
                    die();
                }
                $stats = new Stats($db);

                $home = $e->get_home_team();
                $away = $e->get_away_team();

                $h_h = Event::has_roster($db, $event_id, $home['t_id']);
                $a_h = Event::has_roster($db, $event_id, $away['t_id']);

                $h_stats = $stats->get_stats($event_id, $home['t_id'], $h_h);
                $a_stats = $stats->get_stats($event_id, $away['t_id'], $a_h);

                $home['record'] = SubTeam::get_record($db, $home['t_id']);
                $away['record'] = SubTeam::get_record($db, $away['t_id']);

                $ret = array(
                    'img' => array(
                        'width' => '220',
                        'height' => '220'
                    ),
                    'cols' => $stats->get_cols($event_id),
                    'home' => $home,
                    'away' => $away,
                    'stats' => array_merge($h_stats, $a_stats),
                    'players' => Event::get_players($db, $event_id, $home['t_id'], $away['t_id']),
                );

                include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/user/User.php');
                $ret['p'] = 0;
                if (isset($_SESSION['user']) && $_SESSION['user']->is_admin()){
                    $ret['p']=1;
                }

                echo json_encode($ret);
                die();
                break;
        }


    } else {
        echo 'Invalid request';
        die();
    }
?>