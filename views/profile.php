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
                        <div class="profile-info">
                            <div class="pfp">
                                <img src="<?php echo $view->profile_image(); ?>">
                                <?php if ($can_edit) { ?>
                                    <span id="edit-pfp" class="edit-ctrl">+</span>
                                <?php } ?>
                            </div>
                            <div class="username">
                                <h1 class="username-big">@<?php echo $view->get_username(); ?></h1>
                                <p class="name"><?php echo $view->get_name(); ?></p>
                                <p class="pronouns"><?php echo $view->get_pronouns(); ?></p>
                            </div>
                        </div>
                        <div class="badge-wrapper">
                            <div class="badge-title">
                                <h4>Badges</h4>
                            </div>
                            <div class="badges">
                                <?php
                                    $badges = User::get_badges($view->get_id());
                                    foreach ($badges as $i => $row) {
                                        echo '<img src="'.$row['url'].'" width="100" height="100">';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="banner-bottom">
                        <div class="bio">
                            <p class="bio-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure do</p>
                        </div>
                        <?php if ($can_edit) { ?>
                            <div class="editprev">
                                <button id="edit"><i class='bx bx-edit-alt' ></i>Edit</button>
                                <button id="prev"><i class='bx bx-search-alt' ></i>Preview</button>
                            </div>
                        <?php } else { ?>
                            <div class="profile-ctrls">
                                <button id="like" class="p-btn">
                                    <i id="i-like" class='bx bx-heart'></i>
                                    <p>Like</p>
                                </button>
                                <button id="follow" class="p-btn">
                                    <i id="i-follow" class='bx bx-user-plus'></i>
                                    <p>Follow</p>
                                </button>
                                <button id="dm" class="p-btn">
                                    <i id="i-dm" class='bx bx-chat'></i>
                                    <p>DM</p>
                                </button>
                                <button id="report" class="p-btn">
                                    <i id="i-report" class='bx bx-alarm-exclamation'></i>
                                    <p>Report</p>
                                </button>
                                <button id="block" class="p-btn">
                                    <i class='bx bx-block' ></i></i>
                                    <p>Block</p>
                                </button>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="page-content">
                    <div class="profile-tabs">
                        <button id="tab-info" class="selected">Info</button>
                        <button id="tab-stats">Stats</button>
                        <button id="tab-highlights">Highlights</button>
                    </div>
                    <div class="test">
                        <h2>hi</h2>
                        <h2>hi</h2>
                        <h2>hi</h2>
                        <h2>hi</h2>
                        <h2>hi</h2>
                        <h2>hi</h2>
                        <h2>hi</h2>
                        <h2>hi</h2>
                        <h2>hi</h2>
                        <h2>hi</h2>
                        <h2>hi</h2>
                        <h2>hi</h2>
                        <h2>hi</h2>
                        <h2>hi</h2>
                        <h2>hi</h2>
                    </div>
                </div>
            <? } else { //user doesn't exist ?>

            <?php } ?>
        </section>

        <?php ui_script(); ?>

    </body>
</html>