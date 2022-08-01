<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/classes/util/Sessions.php');

if ((isset($_SERVER['HTTP_X_REQUESTED_WITH'])) && ($_SERVER['HTTP_X_REQUESTED_WITH']==='XMLHttpRequest')) {
    if (!$_SERVER['REQUEST_METHOD']==='POST') {
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
    
    if (isset($_POST['csrf'])) {
        if ($_POST['csrf']!==$_SESSION['csrf']){
            echo json_encode(
                array(
                    'error' => 'Invalid CSRF token'
                )
            );
            die();
        }
    } else {
        echo json_encode(
            array(
                'error' => 'Missing CSRF token'
            )
        );
        die();
    }
    
    if (!isset($_POST['action'])) {
        echo json_encode(
            array(
                'error' => 'Missing action'
            )
        );
        die();
    }

    $action = $_POST['action'];

    switch ($action) {
        case 'like':
            break;
        case 'follow':
            break;
        case 'save':
            
            break;
    }

} else {
    echo 'Access denied';
}

?>