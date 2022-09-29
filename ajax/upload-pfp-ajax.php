<?php
$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/classes/user/User.php');
include_once($path . '/classes/util/ajaxerror.php');
require_once($path . '/classes/util/Sessions.php');

if (!isset($_SESSION['user'])){
    echo 'User session does not exist';
    die();
}

$target_dir = $path . "/uploads" . '/';
$f = $_FILES['fileToUpload'];
$target_file = $target_dir . basename($f['name']);

$type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
$accepted_ext = ['jpg', 'png', 'jpeg', 'gif'];

if (file_exists($target_file)) {
    echo 'File already exists';
    die();
}

if (!in_array($type, $accepted_ext)){
    echo 'Invalid file extension';
    die();
}

if (move_uploaded_file($f['tmp_name'], $target_file)) {
    $user = $_SESSION['user'];
    $status = $user->update_profile_photo($target_file);
    if ($status){
        echo 1;
    }else {
        echo 'File uploaded, but not set as profile photo';
    }
} else {
    echo 'File couldn\'t be uploaded';
}

echo $FILES['fileToUpload']['error'];
die();

?>