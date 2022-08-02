<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/classes/services/RegisterService.php');
require_once($path . '/classes/user/User.php');
require_once($path . '/classes/util/Sessions.php');

if ((isset($_SERVER['HTTP_X_REQUESTED_WITH'])) && ($_SERVER['HTTP_X_REQUESTED_WITH']==='XMLHttpRequest')) {
    if (!$_SERVER['REQUEST_METHOD']==='GET') {
        echo 'Invalid request';
        die();
    }

    if (!isset($_SESSION['user'])) {
        echo 'Invalid request';
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
    
    if (isset($_GET['csrf'])) {
        if ($_GET['csrf']!==$_SESSION['csrf']){
            echo json_encode(
                array(
                    'status' => 0,
                    'errors' => ['Invalid CSRF token']
                )
            );
            die();
        }
    } else {
        echo json_encode(
            array(
                'status' => 0,
                'errors' => ['Missing CSRF token']
            )
        );
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
            $args = $_SERVER['HTTP_REFERER'];
            $arg_arr = explode("/",$args);
            if ($arg_arr[3] !== 'user') {
                echo json_encode(
                    array(
                        'status' => 0,
                        'error' => 'Bad request'
                    )
                );
                die();
            }
            $username = strtolower($arg_arr[4]);
            $user_id = User::find_id($username);
            $ret = User::get_profile_data($user_id);

            echo json_encode(
                array(
                    'status' => 1,
                    'data' => $ret
                )
            );
            break;
    }


} else {
    echo 'Access denied';
}

?>