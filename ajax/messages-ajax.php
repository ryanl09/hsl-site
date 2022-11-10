<?php

require_once($path . '/classes/services/MessageService.php');
require_once($path . '/classes/user/User.php');

$ms = new MessageService($db);

$action='';

switch($_SERVER['REQUEST_METHOD']){
    case 'GET':
        $action = $_GET['action'];
        break;
    case 'POST':
        $action = $_POST['action'];
        break;
}

switch ($action){
    case 'get_convos':
        $convos = $ms->get_convos();

        echo json_encode(
            array(
                'status' => 1,
                'convos' => $convos
            )
        );
        break;
    case 'send_msg':
        break;
    default:
        echo ajaxerror::e('errors', ['Unrecognized action']);
        break;
}

?>