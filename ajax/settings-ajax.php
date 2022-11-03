<?php

include_once('ajax-util.php');

include_once($path . '/classes/security/csrf.php');
include_once($path . '/classes/util/ajaxerror.php');
require_once($path . '/classes/util/Sessions.php');

if (!isset($_SESSION['user'])){
    echo ajaxerror::e('errors', ['No user account found']);
    die();
}

$user = $_SESSION['user'];
$action = $_POST['action'];

switch ($action) {
    case 'set_ign':
        if (!isset($_POST['data'])){
            echo ajaxerror::e('errors', ['Missing fields']);
            die();
        }

        $k = true;
        $data = json_decode($_POST['data'], true);
        for ($i = 0; $i < count($data); $i++){
            $j = $data[$i];
            $l = $user->set_ign($j['game'], $j['ign']);
            $k = $k && $l;
        }

        if (!$k){
            echo ajaxerror::e('errors', ['An error occured when setting 1 or more IGNs']);
            die();
        }

        echo json_encode(
            array(
                'status' => 1,
                'success' => 'IGNs updated!'
            )
        );
        die();
        break;
}

?>