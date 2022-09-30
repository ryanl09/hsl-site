<?php

$path = $_SERVER['DOCUMENT_ROOT'];

require_once('ajax-util.php');
require_once($path . '/classes/event/Event.php');
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
    default:
        echo ajaxerror::e('errors', ['Invalid action']);
        break;
}

?>