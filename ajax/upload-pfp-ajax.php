<?php
include_once($path . '/classes/util/ajaxerror.php');

if (!isset($_POST['submit'])) {
    //invalid request
    die();
}

$target_dir = "uploads/";
$f = $_FILES['fileToUpload'];
$target_file = $target_dir . basename($f['name']);
$ok = 1;
$type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

$check = getimagesize($f['tmp_name']);
if ($check !== false) {
    //image
} else {
    $ok = 0;
}

if (file_exists($target_file)) {
    //file exists
    die();
}

if ($type != 'jpg' && $type != 'png' && $type != 'jpeg' && $type != 'gif'){
    //invalid file type
    die();
}

if (!$ok) {

    die();
}

if (move_uploaded_file($f['tmp_name'], $target_file)) {
    //good
} else {
    //error uploading
}

?>