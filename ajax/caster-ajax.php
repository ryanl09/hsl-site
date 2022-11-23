<?php

$rm = $_SERVER['REQUEST_METHOD'];

require_once($path . '/classes/user/Caster.php');

if (!isset($_SESSION['user'])){
    echo ajaxerror::e('errors', ['Invalid user session']);
    die();
}

$user_id = $_SESSION['user']->get_id();
$c = new Caster($db, $user_id);

if ($rm === 'GET'){
    $action = $_GET['action'];

    switch ($action){
        case 'get_events':
            $events = $c->get_events();

            echo json_encode(
                array(
                    'status' => 1,
                    'events' => $events
                )
            );
            break;
    }
} else if ($rm === 'POST') {
    $action = $_POST['action'];

    if (!isset($_POST['event_id'])){
        echo ajaxerror::e('errors', ['Missing event id']);
        die();
    }

    $event_id = $_POST['event_id'];

    switch ($action){
        case 'add_event':
            $add = $c->add_event($event_id);

            echo json_encode(
                array(
                    'status' => 1,
                    'success' => $add
                )
            );
            break;
        case 'remove_event':
            $rem = $c->remove_event($event_id);

            echo json_encode(
                array(
                    'status' => 1,
                    'success' => $add
                )
            );
            break;
    }
} else {
    echo ajaxerror::e('errors', ['Invalid request'];)
}

?>