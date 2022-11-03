<?php

require_once($path . '/classes/general/Season.php');
require_once($path . '/classes/team/SubTeam.php');
require_once($path . '/classes/user/TempUser.php');
require_once($path . '/classes/user/User.php');

if (!isset($_SESSION['user']))
{
    echo ajaxerror::e('errors', ['Invalid permissions']);
    die();
}

if (!$_SESSION['user']->is_admin()) {
    echo ajaxerror::e('errors', ['Invalid permissions']);
    die();
}

$post = check_post();
if (!$post['status']) {
    echo ajaxerror::e('errors', [$get['error']]);
    die();
}

$csrf = CSRF::post();
if (!$csrf) {
    echo ajaxerror::e('errors', ['Invalid CSRF token']);
    die();
}