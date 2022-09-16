<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');

$redir=false;
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];

    if (!$user->is_admin()) {
        $redir=true;
    }
} else {
    $redir=true;
}

if ($redir){
    header('Location: /dashboard');
}

start_content_full(1, 'graphics');
end_content_full(1);

?>