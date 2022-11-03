<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/user/User.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/ajax/ajaxdb.php');

$db = ajaxdb::get_instance();

if (!session_id()) {
    session_start();
}

if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(12));
}

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $user_id = $user->get_id();
    $_SESSION['user'] = new User($db, $user_id);
}

$args = $_SERVER["REQUEST_URI"];
str_replace('://', '', $args);
$_SESSION['current_page'] = explode("/", $args);

?>