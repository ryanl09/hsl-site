<?php

if (!session_id()) {
    session_start();
}

if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(12));
}

if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user']->get_id();
    $_SESSION['user'] = new User($user_id);
}

$args = $_SERVER["REQUEST_URI"];
str_replace('://', '', $args);
$_SESSION['current_page'] = explode("/", $args);

?>