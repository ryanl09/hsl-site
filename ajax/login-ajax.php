<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/classes/services/LoginService.php');
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
    
    $ls = new LoginService($_POST);
    $login = $ls->login();

    echo json_encode($login);


} else {
    echo 'Access denied';
}

?>