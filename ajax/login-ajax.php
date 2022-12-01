<?php

require_once($path . '/classes/security/Nonce.php');
require_once($path . '/classes/services/LoginService.php');

if (!$_SERVER['REQUEST_METHOD']==='POST') {
    ajaxerror::e('errors', ['Invalid request']);
    die();
}

if (!isset($_POST['nonce'])){
    echo ajaxerror::e('errors', ['Missing session key']);
    die();
}

$nonce = $_POST['nonce'];
$verify = Nonce::verify($nonce);
if (!$verify){
    echo ajaxerror::e('errors', ['Invalid session key. ']);
    die();
}
Nonce::destroy();

$ls = new LoginService($db, $_POST);
$login = $ls->login();

echo json_encode($login);

?>