<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once('ajax-util.php');
include_once($path . '/classes/security/csrf.php');
require_once($path . '/classes/services/ForgotPasswordService.php');
include_once($path . '/classes/util/ajaxerror.php');
require_once($path . '/classes/util/Sessions.php');

$post = check_post();
if (!$post['status']) {
    echo ajaxerror::e('errors', [$post['error']]);
    die();
}

$csrf = CSRF::post();
if (!$csrf) {
    echo ajaxerror::e('errors', ['Invalid CSRF token']);
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

$fps = new ForgotPasswordService();

$action = $_POST['action'];

switch ($action) {
    case 'request':
        if (!isset($_POST['email'])){
            echo ajaxerror::e('errors', ['Missing email']);
            die();
        }

        $email = $_POST['email'];
        $res = $fps->send_code($email);

        if ($res['status']===1){
            echo json_encode($res);
        }

        echo ajaxerror::e('errors', [$res['error']]);

        break;
    case 'reset':
        if (!isset($_POST['pass'])){
            echo ajaxerror::e('errors', ['Missing password']);
            die();
        }

        $pass=$_POST['pass'];
        $r = $fps->reset($pass);

        if ($r) {
            echo json_encode(
                array(
                    'status' => 1,
                    'success' => 'Your password has been reset!'
                )
            );
        }

        echo ajaxerror::e('errors', ['Could not reset your password. Please try again later']);
        break;
}

?>