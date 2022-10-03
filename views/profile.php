<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');
require_once($path . '/classes/general/Season.php');
require_once($path . '/classes/user/User.php');
require_once($path . '/classes/util/tecdb.php');

$arg_arr = $_SESSION['current_page'];
$username = strtolower($arg_arr[2]);
$view = User::get_class_instance(0, $username);

$fill = '';
$can_edit = false;
$edit_style = '';

if (isset($_SESSION['user'])){
    if ($view->get_id() === $_SESSION['user']->get_id()) {
        $can_edit=true;
        $fill = ' edit--fill';
        $edit_style = array(
            '.p-c' => 'display:none;',
            '.p-c[style*="display: block"]' => 'display:flex !important;',
        );
    }else {
        $edit_style=array(
            '.e-c' => 'display:none;'
        );
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<?php 
base_header(
    array(
        'styles' => ['profile'],
        'scripts' => ['profile'],
        'custom_style' => $edit_style
    )
); 
?>
    <body>
        <?php print_navbar();?>
        <?php if ($can_edit) { ?>
            <div class="editprev">
                 <button id="edit" class="c-mode"><i class='bx bx-edit-alt' ></i>Edit</button>
                <button id="prev"><i class='bx bx-search-alt' ></i>Preview</button>
            </div>
        <?php } ?>
        <section class="home">
            <?php if ($view->get_id()) { //if user exists ?>
                <div class="banner-wrapper">
                    <div class="banner">
                        <div class="profile-info">
                            <div class="pfp">
                                <img src="<?php echo $view->profile_image(); ?>">
                                <?php if ($can_edit) { ?>
                                    <form id="pfp-form" action="https://tecesports.com/ajax/upload-pfp-ajax.php" method="post" enctype="multipart/form-data" style="display:none;">
                                        <input accept=".jpg, .png, .jpeg, .gif" type="file" name="fileToUpload" id="fileToUpload">
                                        <input type="submit" name="submit" class="e-c" value="+">
                                    </form>
                                    <span id="edit-pfp" class="e-c">+</span>
                                <?php } ?>
                                <div class="online-status <?php echo ($can_edit ? 'set-online-status' : ''); ?>">
                                    <?php if ($can_edit) { ?>
                                        <i class='bx bx-dots-horizontal-rounded e-c' ></i>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="username">
                                <h1 class="username-big">@<?php echo $view->get_username(); ?><i class='bx bxs-check-square'></i></h1>
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
                                    if (empty($badges)) {
                                        echo '<p class="e-c">You don\'t have any badges yet!</p>';
                                        echo '<p class="p-c">No badges on display</p>';
                                    } else {
                                        foreach ($badges as $i => $row) {
                                            echo '<img src="'.$row['url'].'" width="100" height="100">';
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="school-logo">
                            <img src="<?php echo $view->get_team()->get_logo(); ?>" alt="logo" width="100" height="100">
                        </div>
                    </div>
                    <div class="banner-bottom">
                        <div class="bio">
                            <?php if ($can_edit) { ?>
                                <textarea class="bio-text-edit e-c <?php echo $fill; ?>"></textarea>
                            <?php } ?>
                            <p class="bio-text p-c"></p>
                            <div class="profile-ctrls p-c">
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
                    </div>
                </div>
                <div class="page-content">
                <input type="hidden" id="csrf" value="<?php echo $_SESSION['csrf']; ?>">
                    <div class="profile-tabs">
                        <button id="tab-info" class="selected">Info</button>
                        <button id="tab-stats">Stats</button>
                        <button id="tab-highlights">Highlights</button>
                    </div>
                    <div class="info-tab --tab">
                        <div class="tab">
                            <div class="row e3">
                                <div class="loading box-info"></div>
                                <div class="loading box-info"></div>
                                <div class="loading box-info"></div>
                                <div class="box">
                                    <div class="info">
                                        <h4><i class='bx bxs-school'></i>School</h4>
                                        <p id="student-school-value"></p>
                                    </div>
                                    <div class="info grad-year-info">
                                        <h4><i class='bx bxs-graduation' ></i>Year of Graduation</h4>
                                        <p id="grad-year-value"></p>
                                    </div>
                                    <div class="info twitch-info end">
                                        <h4><i class='bx bxl-twitch twitch'></i>Twitch</h4>
                                        <a id="twitch-value"></a>
                                    </div>
                                </div>
                                <div class="box">
                                    <div class="info" id="games-info">
                                        <h4>Games</h4>
                                    </div>
                                </div>
                                <div class="box">
                                    <div class="info end">
                                        <div class="info">
                                            <h4>Upcoming Matches</h4>
                                        </div>
                                        <table style="width:100%;">
                                            <thead>
                                                <th>Game</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                            </thead>
                                            <tbody id="ucmatches">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="stats-tab --tab" style="display:none;">
                        <div class="tab">
                            <div class="row">
                                <div class="box">
                                    <div class="selectors">
                                        <div class="selector">
                                            <label for="season">Season:</label>
                                            <select name="season" id="season">
                                                <?php
                                                    $c_s = Season::get_current();
                                                    $a = Season::get_all_prior();
                                                    foreach ($a as $i => $row){
                                                        echo '<option value="'.$row['id'].'">'.$row['season_name'].'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="selector">
                                            <label for="game">Game:</label>
                                            <select name="game" id="game">
                                                <?php
                                                    $u = $view->games_competing_in($c_s);
                                                    print_r($u);
                                                    foreach($u as $i => $row){
                                                        echo '<option value="'.$row['id'].'">'.$row['game_name'].'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <script type="text/javascript">
                                        $('#season').val(<?php echo $c_s; ?>);
                                    </script>

                                    <div class="tbl-stats-wrapper">
                                        <table cellspacing="0">
                                            <thead class="tbl-stats-thead"></thead>
                                            <tbody class="tbl-stats-tbody"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-highlights --tab" style="display:none;">
                        <div class="tab">

                        </div>
                    </div>
                </div>
            <?php } else {  ?>
                <div class="user-dne">
                    <p>User not found!</p>
                </div>
            <?php } ?>
        </section>

        <?php ui_script(); ?>

    </body>
</html>