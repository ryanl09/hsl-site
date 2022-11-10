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
    die();
}

$last = time();
$started = time();

$db = ajaxdb::get_instance();
$ms = new MessageService($db);

session_write_close();

$msgs = $ms->get_since($last);
if (!empty($msgs)){
    foreach ($msgs as $i => $row){
        echo "data:\n";
        echo "from: " . $row['id_from'] . ', sent at: ' . $row['time_sent'] . ', msg: ' . $row['message'];
        ob_flush();
        flush();
    }
}

if ((time() - $started) > 60) {
    session_start();
    die();
}

$last = time();
sleep(2);

?>