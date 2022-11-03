<?php

include_once('ajax-util.php');

$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path . '/classes/security/csrf.php');
include_once($path . '/classes/util/ajaxerror.php');
require_once($path . '/classes/util/Sessions.php');

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

?>