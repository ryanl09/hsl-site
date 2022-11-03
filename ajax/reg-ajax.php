<?php


include_once('ajax-util.php');

$path = $_SERVER['DOCUMENT_ROOT'];

if (!isset($_SESSION['user'])){
    echo ajaxerror::e('errors', ['No user account found']);
    die();
}

$user = $_SESSION['user'];

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