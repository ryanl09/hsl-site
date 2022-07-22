<?php

if (!session_id()) {
    session_start();
}

if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(12));
}

?>