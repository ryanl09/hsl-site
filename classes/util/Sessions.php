<?php

if (!session_id()) {
    session_start();
}

if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(12));
}

$args = $_SERVER["REQUEST_URI"];
$_SESSION['current_page'] = explode("/",$args);

?>