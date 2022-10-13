<?php


include_once('ajax-util.php');

$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path . '/classes/security/csrf.php');
include_once($path . '/classes/util/ajaxerror.php');
include_once($path . '/classes/util/tecdb.php');
require_once($path . '/classes/util/Sessions.php');

if (!isset($_SESSION['user'])){
    echo ajaxerror::e('errors', ['No user account found']);
    die();
}

$user = $_SESSION['user'];

$post = check_post();
if (!$post['status']) {
    echo ajaxerror::e('errors', [$post['error']]);
    die();
}

$csrf = CSRF::post();
if (!$csrf) {
    echo ajaxerror::e('errors', ['Invalid CSRF token']);
    die();
}

/*

if (!isset($_POST['action'])) {
    echo json_encode(
        array(
            'error' => 'Missing action'
        )
    );
    die();
}
*/

if (isset($_POST['update_team'])){
    if (isset($_SESSION['user']) && $_SESSION['user']->is_admin()){
        $query = 
        "UPDATE `users`
        SET `team_id` = ?
        WHERE `user_id` = ?";
        $db = new tecdb();

        $res = $db->query($query, $_POST['update_team'], $_SESSION['user']->get_id())->affectedRows();
        echo json_encode(
            array(
                'status'=>1
            )
        );
    }else{
        echo ajaxerror::e('errors', ['Invalid permission']);
    }
    
}else{
    echo ajaxerror::e('errors', ['wrong field']);
}

?>