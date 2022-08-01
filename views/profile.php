<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');
require_once($path . '/classes/user/User.php');

$args = $_SERVER["REQUEST_URI"];
$arg_arr = explode("/",$args);

$username = strtolower($arg_arr[2]);
$view = User::get_class_instance(0, $username);

$can_edit = false;
if ($view->get_id() === $_SESSION['user']->get_id()) {
    $can_edit=true;
}

?>

<!DOCTYPE html>
<html lang="en">
<?php 
base_header(
    array(
        'styles' => ['profile'],
        'scripts' => ['profile']
    )
); 
?>
    <body>
        <?php print_navbar();?>
        <section class="home">
            <?php if ($view->get_id()) { //if user exists ?>
                <div class="banner-wrapper">
                    <div class="banner">
                        <div class="pfp">
                            <img src="<?php echo $view->profile_image(); ?>">
                            <?php if ($can_edit) { ?>
                                <span id="edit-pfp" class="edit-ctrl">+</span>
                            <?php } ?>
                        </div>
                        <div class="username">
                            <h1 class="username-big"><?php echo $view->get_username(); ?></h1>
                        </div>
                        <?php if ($can_edit) { ?>
                            <div class="editprev">
                                <button id="edit">Edit</button>
                                <button id="prev">Preview</button>
                            </div>
                        <?php } else { ?>
                            <div class="profile-ctrls">
                                <button id="like" class="p-btn"><i id="i-like" class='bx bx-heart'></i>Like</button>
                                <button id="follow" class="p-btn"><i id="i-follow" class='bx bx-user-plus'></i>Follow</button>
                                <button id="dm" class="p-btn"><i id="i-dm" class='bx bx-chat'></i>DM</button>
                                <button id="report" class="p-btn"><i id="i-report" class='bx bx-alarm-exclamation'></i>Report</button>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <? } else { //user doesn't exist ?>

            <?php } ?>
            <div class="page-content"></div>
        </section>

        <?php ui_script(); ?>

    </body>
</html>