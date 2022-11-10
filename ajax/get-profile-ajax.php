<?php

require_once($path . '/classes/general/Stats.php');
require_once($path . '/classes/user/User.php');
include_once($path . '/classes/util/ajaxerror.php');
require_once($path . '/classes/util/Sessions.php');

if (!$_SERVER['REQUEST_METHOD']==='GET') {
    echo 'Invalid request';
    die();
}

if (isset($_GET['tab'])) {
    if (!$_GET['tab']) {
        echo json_encode(
            array(
                'status' => 0,
                'errors' => ['Invalid tab']
            )
        );
        die();
    }
} else {
    echo json_encode(
        array(
            'status' => 0,
            'errors' => ['Missing tab']
        )
    );
    die();
}

$tab = $_GET['tab'];

switch ($tab) {
    case 'info':
        if (!isset($_GET['user'])){
            echo ajaxerror::e('errors', ['Missing user']);
            die();
        }
        $username = strtolower($_GET['user']);
        $user_id = User::find_id($db, $username);

        $user = new User($db, $user_id);
        $ret = $user->get_profile_data();
        $events = $user->get_events();

        echo json_encode(
            array(
                'status' => 1,
                'data' => $ret,
                'events' => $events
            )
        );
        break;
    case 'get_stats':
        if (!isset($_GET['pl_id']) || !isset($_GET['game']) || !isset($_GET['season'])){
            echo ajaxerror::e('errors', ['Missing fields']);
            die();
        }
        
        if (!isset($_GET['user'])){
            echo ajaxerror::e('errors', ['Missing user']);
            die();
        }
        $username = strtolower($_GET['user']);
        $user_id = User::find_id($db, $username);
        $game=$_GET['game'];
        $season=$_GET['season'];

        $s=new Stats($db);
        
        echo json_encode(
            array(
                'status' => 1,
                'cols' => $s->get_cols_game($game),
                'stats'=>$s->get($user_id, $season, $game)
            )
        );
        break;
}

?>