<?php

require_once($path . '/classes/security/Nonce.php');
require_once($path . '/classes/services/RegisterService.php');

if (!$_SERVER['REQUEST_METHOD']==='POST') {
    echo 'Invalid request';
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

$rs = new RegisterService($db, $_POST, $type);
$reg = $rs->register();

echo json_encode($reg);
?>