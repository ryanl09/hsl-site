<?php

require_once('ajax-util.php');
require_once($path . '/classes/services/ForgotPasswordService.php');

$fps = new ForgotPasswordService($db);

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
            die();
        }

        echo ajaxerror::e('errors', [$res['error']]);
        die();
        break;
    case 'reset':
        if (!isset($_POST['password']) || !isset($_POST['cpassword'])){
            echo ajaxerror::e('errors', ['Missing password']);
            die();
        }

        $pass=$_POST['password'];
        $cpass=$_POST['cpassword'];

        if (strcmp($pass, $cpass) !== 0){
            echo ajaxerror::e('errors', ['Passwords must match!']);
            die();
        }

        $r = $fps->reset($pass);

        if ($r) {
            echo json_encode(
                array(
                    'status' => 1,
                    'success' => 'Your password has been reset!'
                )
            );
            die();
        }

        echo ajaxerror::e('errors', ['Could not reset your password. Please try again later']);
        break;
}

?>