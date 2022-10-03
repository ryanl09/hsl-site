<?php

if (!isset($_SESSION['user']) && (strtolower($_SESSION['current_page'][1]) !== 'events')) {
    header('Location: /login');
}

?>