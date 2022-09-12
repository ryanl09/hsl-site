<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/classes/services/RegisterService.php');
include_once($path . '/classes/util/ajaxerror.php');
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

    if (isset($_POST['terms'])) {
        $terms = $_POST['terms'];
        if (!$terms) {
            echo json_encode(
                array(
                    'status' => 0,
                    'errors' => ['You must accept the Terms & Conditions and Privacy Policy to register']
                )
            );
        }
    } else {
        echo json_encode(
            array(
                'status' => 0,
                'errors' => ['Missing terms']
            )
        );
        die();
    }

    $type = $_POST['type'];
    if (isset($_POST['type'])) {
        if (!$type) {
            echo json_encode(
                array(
                    'status' => 0,
                    'errors' => ['Invalid user type']
                )
            );
            die();
        }
    } else {
        echo json_encode(
            array(
                'status' => 0,
                'errors' => ['Missing user type']
            )
        );
        die();
    }

    $rs = new RegisterService($_POST, $type);
    $reg = $rs->register();

    echo json_encode($reg);


} else {
    echo 'Access denied';
}

?>