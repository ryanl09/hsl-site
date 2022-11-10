<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/classes/util/ClientRequest.php');
require_once($path . '/classes/util/Sessions.php');

$arg_arr = $_SESSION['current_page'];
//$page = strtolower($arg_arr[1]);

$page = isset($_GET['params']) ? $_GET['params'] : '';

if ($page!=='css'){
    $req = new ClientRequest($page);   
}

?>