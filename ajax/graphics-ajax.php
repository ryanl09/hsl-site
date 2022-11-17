<?php

require_once($path . '/classes/general/Season.php');
require_once($path . '/classes/graphics/GImage.php');
require_once($path . '/classes/user/User.php');

if (!isset($_SESSION['user']))
{
    echo ajaxerror::e('errors', ['Invalid permissions']);
    die();
}

if (!$_SESSION['user']->is_admin()) {
    echo ajaxerror::e('errors', ['Invalid permissions']);
    die();
}

$type = $_SERVER['REQUEST_METHOD'];

if ($type==='GET'){
    $action = $_GET['action'];

    switch ($action){
        case 'get_data':
            if (!isset($_GET['upload_id'])){
                echo ajaxerror::e('errors', ['Missing id']);
                die();
            }

            $uid = $_GET['upload_id'];
            $img = new GImage($db, $uid);
            $data = $img->get_data();

            echo json_encode(
                array(
                    'status' => 1,
                    'data' => $data
                )
            );
            break;
        case 'get_matches':

            echo json_encode(
                array(
                    'status' => 1
                )
            );
            break;
        case 'get_standings':

            echo json_encode(
                array(
                    'status' => 1
                )
            );
            break;
        case 'get_scores':

            echo json_encode(
                array(
                    'status' => 1
                )
            );
            break;
        case 'get_roster':

            echo json_encode(
                array(
                    'status' => 1
                )
            );
            break;
    }
} else if ($type==='POST'){
    $action = $_POST['action'];

    $uid = $_POST['upload_id'];
    $img = new GImage($db, $uid);
    
    $action = $_POST['action'];
    
    switch ($action){
        case 'set_data':
            if (!isset($_POST['data'])){
                echo ajaxerror::e('errors', ['Missing fields']);
                die();
            }
    
            $data = json_decode($_POST['data'], true);
            $set = $img->set_data($data);
    
            echo json_encode(
                array(
                    'status' => 1,
                    'set' => $set
                )
            );
            break;
    }

} else {
    echo ajaxerror::e('errors', ['Invalid request']);
}