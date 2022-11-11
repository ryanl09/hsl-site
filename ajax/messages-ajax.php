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
        if (!isset($_POST['msg']) || !isset($_POST['to'])){
            echo ajaxerror::e('errors', ['Missing fields']);
            die();
        }

        $msg = $_POST['msg'];
        $to = $_POST['to'];
        $sent = $ms->send($to, $msg);
        if ($sent){
            echo json_encode(
                array(
                    'status' => 1
                )
            );
            die();
        }

        echo ajaxerror::e('errors', ['Could not send message']);
        break;
    case 'get_convo':
        if (!isset($_GET['user_id'])){
            echo ajaxerror::e('errors', ['Missing user']);
            die();
        }

        $lim = 0;
        if (isset($_GET['lim'])){
            $lim=$_GET['lim'];
        }

        $user_id = $_GET['user_id'];
        $convo = $ms->get_convo($user_id, $lim);

        echo json_encode(
            array(
                'status' => 1,
                'convo' => $convo
            )
        );
        break;
    default:
        echo ajaxerror::e('errors', ['Unrecognized action']);
        break;
}

?>