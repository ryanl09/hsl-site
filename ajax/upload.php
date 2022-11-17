<?php
$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/ajax/ajaxdb.php');
require_once($path . '/classes/user/User.php');
require_once($path . '/classes/graphics/GImage.php');
include_once($path . '/classes/util/ajaxerror.php');
require_once($path . '/classes/util/Sessions.php');

if (!isset($_SESSION['user'])){
    echo 'User session does not exist';
    die();
}

$db = ajaxdb::get_instance();
$user = $_SESSION['user'];

$target_dir = $path . "/uploads/";

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
    $u_id = GImage::insert($db, '/uploads/' . $new_f);
    if ($u_id){
        echo json_encode(
            array(
                'status'=>1,
                'id' => $u_id,
                'url'=> 'https://tecesports.com/uploads/' . $new_f
            )
        );
    }else {
        echo ajaxerror::e('errors', ['File uploaded, but an error occured']);
    }
} else {
    echo ajaxerror::e('errors', ['Couldn\'t upload file']);
}

die();

?>