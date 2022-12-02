<?php

require_once('ajax-util.php');
require_once('ajaxdb.php');

$db = ajaxdb::get_instance();

$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path . '/classes/security/csrf.php');
include_once($path . '/classes/util/ajaxerror.php');
include_once($path . '/classes/util/Sessions.php');

require_once($path . '/classes/util/RateLimiter.php');

$rl = new RateLimiter(10, 3);
$add = $rl->add();

if (!$add){
    $time = $rl->time_until_next();

    if ($time > 0){
        echo ajaxerror::e('errors', ['Too many requests. Please wait ' . $time . ' seconds']);
        die();
    }

    //fail safe
    $rl->allow();
}

$get = check_get();
$post = check_post();
$status = $get['status'] || $post['status'];

if (!$status){
    echo ajaxerror::e('errors', ['Invalid request']);
    die();
}

if ($post['status']){
    $page = $_POST['page'];
    $csrf = CSRF::post();
    if (!$csrf){
        echo ajaxerror::e('errors', ['Invalid CSRF token']);
        die();
    }

    if (!isset($_POST['action'])){
        echo ajaxerror::e('errors', ['Action not set']);
        die();
    }
} else {
    $page = $_GET['page'];
    $csrf = CSRF::get();
    if (!$csrf){
        echo ajaxerror::e('errors', ['Invalid CSRF token']);
        die();
    }

    if (!isset($_GET['action'])){
        echo ajaxerror::e('errors', ['Action not set']);
        die();
    }
}

$exists = file_exists($path . '/ajax' . '/' . $page . '-ajax.php');
if(!$exists){
    echo ajaxerror::e('errors', ['Resource not found']);
    die();
}

$method = $_SERVER['REQUEST_METHOD'];
include_once($path . '/ajax' . '/' . $page . '-ajax.php');


?>