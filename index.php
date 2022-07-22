<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/classes/util/ClientRequest.php');
require_once($path . '/classes/util/Sessions.php');

$args = $_SERVER["REQUEST_URI"];
$arg_arr = explode("/",$args);
$page = strtolower($arg_arr[1]);

$req = new ClientRequest($page);

?>