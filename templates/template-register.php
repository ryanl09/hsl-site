<?php

if (!isset($_POST['registerbtn'])) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = tec::safe($_POST['username']);
        $password = tec::safe($_POST['password']);
        $rememberme = isset($_POST['rememberme']) && !empty($_POST['rememberme']);
    }
}

?>


<?php ?>