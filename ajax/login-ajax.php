<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/classes/services/LoginService.php');

if (!$_SERVER['REQUEST_METHOD']==='POST') {
    echo 'Invalid request';
    die();
}

$ls = new LoginService($db, $_POST);
$login = $ls->login();

echo json_encode($login);

?>