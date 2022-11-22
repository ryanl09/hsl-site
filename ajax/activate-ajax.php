<?php

require_once('ajax-util.php');
require_once($path . '/classes/services/ConfirmEmailService.php');

$ces = new ConfirmEmailService($db);

$action = $_POST['action'];

switch ($action) {
    case 'activate_account':
        if (!isset($_POST['email'])) {
            echo ajaxerror::e('errors', ['Missing email']);
            die();
        }

        if (!isset($_POST['activation_key'])) {
            echo ajaxerror::e('errors', ['Missing activation key']);
            die();
        }

        $email = $_POST['email'];
        $res = $ces->send_confirmation_email($email);

        if ($res['status']===1) {
            echo json_encode($res);
            die();
        }

        echo ajaxerror::e('errors', [$res['error']]);
        die();
        break;
}

?>