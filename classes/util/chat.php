<?php

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

$path = $_SERVER['DOCUMENT_ROOT'];

require_once('Sessions.php');
require_once($path . '/ajax/ajaxdb.php');
require_once($path . '/classes/services/MessageService.php');
require_once($path . '/classes/user/User.php');

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

if (!isset($_SESSION['user'])){
    //die();
}

$now = date('Y-m-d H:i:s');
$last = $now;

if (isset($_SESSION['last_check'])){
    $last = $_SESSION['last_check'];
}

$db = ajaxdb::get_instance();
$ms = new MessageService($db);

$id = $ms->get_id();

$msgs = $ms->get_since($last, $now);
echo "data:";
echo json_encode($msgs);
echo "\n\n";
flush();

$_SESSION['last_check'] = $now;

session_write_close();

?>