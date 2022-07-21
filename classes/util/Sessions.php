<?php

if (isset($_COOKIE['PHPSESSID'])) {
    session_id($_COOKIE['PHPSESSID']);
}

if (!session_id()) {
    session_start();
}

if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(12));
}

?>