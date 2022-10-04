<?php

$path = $_SERVER['DOCUMENT_ROOT'];

require_once('ajax-util.php');
require_once($path . '/classes/event/Event.php');
require_once($path . '/classes/general/Game.php');
include_once($path . '/classes/security/csrf.php');
include_once($path . '/classes/util/ajaxerror.php');
require_once($path . '/classes/util/Sessions.php');

$get = check_get();
if (!$get['status']) {
    echo ajaxerror::e('errors', [$get['error']]);
    die();
}

$csrf = CSRF::get();
if (!$csrf) {
    echo ajaxerror::e('errors', ['Invalid CSRF token']);
    die();
}

if (!isset($_GET['action'])) {
    echo json_encode(
        array(
            'error' => 'Missing action'
        )
    );
    die();
}

$action = $_GET['action'];

switch ($action) {
    case 'get_today':
        if (!isset($_GET['game'])){
            echo ajaxerror::e('errors', ['Game not set']);
        }

        $game = $_GET['game'];
        $events = Event::all_today_game($game);
        echo json_encode(
            array(
                'status' => 1,
                'events' => $events
            )
        );
        die();
        break;
    case 'get_current':
        $e = Event::is_now();
        echo json_encode(
            array(
                'status' => 1,
                'now' => $e,
                'next' => Event::get_next()
            )
        );
        die();
        break;
    case 'all_events':
        if (!isset($_GET['sort-team']) || !isset($_GET['sort-div']) || !isset($_GET['game'])){
            echo ajaxerror::e('errors', ['Missing fields']);
            die();
        }

        $game=$_GET['game'];
        $team = $_GET['sort-team'];
        $div = $_GET['sort-div'];

        if (!is_numeric($team) || !is_numeric($div) || !is_numeric($game)){
            echo ajaxerror::e('errors', ['Invalid team or division, ' . $team . ', ' . $div]);
            die();
        }

        $e = [];
        $e = Event::sort_by($game, $team, $div);

        echo json_encode(
            array(
                'status' => 1,
                'events' => $e
            )
        );
        die();
        break;
    case 'get_teams':
        if (!isset($_GET['game']) || !isset($_GET['div'])){
            echo ajaxerror::e('errors', ['No game or division set']);
            die();
        }
        
        $game = $_GET['game'];
        $div = $_GET['div'];

        $t = Game::get_teams($game, $div);
        echo json_encode(
            array(
                'status' => 1,
                'teams' => $t
            )
        );
        die();
        break;
    default:
        echo ajaxerror::e('errors', ['Invalid action']);
        break;
}

?>