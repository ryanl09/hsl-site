<?php
$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/classes/user/User.php');
include_once($path . '/classes/util/ajaxerror.php');
require_once($path . '/classes/util/Sessions.php');
require_once($path . '/classes/util/tecdb.php');

if (!isset($_SESSION['user'])){
    echo 'User session does not exist';
    die();
}

$user = $_SESSION['user'];

$db = new tecdb();

$query=
"SELECT `pfp_url`
FROM `users`
WHERE `user_id` = ?";
$res = $db->query($query, $user->get_id())->fetchArray();

$target_dir = $path . "/uploads" . '/';

if ($res['pfp_url']){
    unlink($target_dir . $res['pfp_url']);
}


$f = $_FILES['fileToUpload'];
$target_file = $target_dir . basename($f['name']);

$type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
$accepted_ext = ['jpg', 'png', 'jpeg', 'gif'];

$new_f = $user->get_username();
$new_f .= '-' . bin2hex(random_bytes(5)) . '.' . $type;

$target_file = $target_dir . $new_f;

if (file_exists($target_file)) {
    echo ajaxerror::e('errors', ['File already exists on server. Please try again']);
    die();
}

if (!in_array($type, $accepted_ext)){
    echo ajaxerror::e('errors', ['Invalid file extension. Accepted formats: *.jpg, *.png, *.jpeg, *.gif']);
    die();
}

if (move_uploaded_file($f['tmp_name'], $target_file)) {
    $status = $user->update_profile_photo($new_f);
    if ($status){
        echo json_encode(
            array(
                'status'=>1,
                'url'=> 'https://tecesports.com/uploads/' . $new_f
            )
        );
    }else {
        echo ajaxerror::e('errors', ['File uploaded, but not set as profile photo']);
    }
} else {
    echo ajaxerror::e('errors', ['Couldn\'t upload file']);
}

die();

?>