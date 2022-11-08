<?php

require_once($path . '/classes/general/Season.php');
require_once($path . '/classes/graphics/GImage.php');
require_once($path . '/classes/team/SubTeam.php');
require_once($path . '/classes/user/TempUser.php');
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

if (!isset($_POST['upload_id'])){
    echo ajaxerror::e('errors', ['Missing fields']);
    die();
}

$uid = $_POST['upload_id'];
$img = new GImage($db, $uid);

$action = $_POST['action'];

switch ($action){
    case 'get_data':
        echo json_encode(
            array(
                'status' => 1,
                'data' => $img->get_data()
            )
        );
        break;

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