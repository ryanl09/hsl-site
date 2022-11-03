<?php

require_once('ajax-util.php');
require_once('ajaxdb.php');

$db = ajaxdb::get_instance();

$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path . '/classes/security/csrf.php');
include_once($path . '/classes/util/ajaxerror.php');
include_once($path . '/classes/util/Sessions.php');

$get = check_get();
$post = check_post();
$status = $get['status'] || $post['status'];

if (!$status){
    echo ajaxerror::e('errors', ['Invalid request']);
    die();
}

$page = $_GET['page'];
if ($post['status']){
    $page = $_POST['page'];
    $csrf = CSRF::post();
    if (!$csrf){
        echo ajaxerror::e('errors', ['Invalid CSRF token']);
        die();
    }
} else {
    $csrf = CSRF::get();
    if (!$csrf){
        echo ajaxerror::e('errors', ['Invalid CSRF token']);
        die();
    }
}

$exists = file_exists($path . '/ajax' . '/' . $page . '-ajax.php');
if(!$exists){
    echo ajaxerror::e('errors', ['Resource not found']);
    die();
}

include_once($path . '/ajax' . '/' . $page . '-ajax.php');


?>