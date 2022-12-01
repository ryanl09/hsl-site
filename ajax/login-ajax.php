<?php

require_once($path . '/classes/security/Nonce.php');
require_once($path . '/classes/services/LoginService.php');

if (!$_SERVER['REQUEST_METHOD']==='POST') {
    echo ajaxerror::e('errors', ['Invalid request']);
    die();
}

if (!isset($_POST['cap'])){
    echo ajaxerror::e('errors', ['Missing captcha!']);
    die();
}

$cap = $_POST['cap'];

$secret = '6LeI7ksjAAAAAFhzmM7Ma49AFf1FsMwH05a9WeId';
$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$cap);
$responseData = json_decode($verifyResponse);

if (!$responseData->success){
    echo ajaxerror::e('errors', ['Invalid captcha!']);
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