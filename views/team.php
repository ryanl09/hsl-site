<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');
require_once($path . '/classes/general/Badge.php');
require_once($path . '/classes/general/Season.php');
require_once($path . '/classes/team/Team.php');
require_once($path . '/classes/user/User.php');

$arg_arr = $_SESSION['current_page'];
$team_name = strtolower($arg_arr[2]);
$team_id = Team::from_slug($db, $team_name);
$team = new Team($db, $team_id);

$edit_style='';
$can_edit = false;

?>

<!DOCTYPE html>
<html lang="en">
<?php 
base_header(
    array(
        'styles' => ['team'],
        'scripts' => ['team'],
        'custom_style' => $edit_style
    )
); 
?>
    <body>
        <?php print_navbar();?>
        <section class="home">
            <?php if ($team->get_id()) { //if user exists ?>
                <div class="banner-wrapper">
                    <div class="banner">
                        <div class="profile-info">
                            <div class="pfp" style="background: url(<?php echo $team->get_logo(); ?>) 50% 50% no-repeat;";>
                                <!--<img src="<?php //echo $view->profile_image(); ?>">-->
                                <?php if ($can_edit) { ?>
                                    <!--
                                    <form id="pfp-form" action="https://tecesports.com/ajax/upload-pfp-ajax.php" method="post" enctype="multipart/form-data" style="display:none;">
                                        <input accept=".jpg, .png, .jpeg, .gif" type="file" name="fileToUpload" id="fileToUpload">
                                        <input type="submit" name="submit" class="e-c" value="+">
                                    </form>
                                    <span id="edit-pfp" class="e-c">+</span> -->
                                <?php } ?>
                            </div>
                            <div class="username">
                                <h1 class="username-big">@<?php echo $team_name; ?><i class='bx bxs-check-square'></i></h1>
                                <p class="name"><?php echo $team->get_team_name(); ?></p>
                                <p class="name">
                                    <?php
                                        $tm = $team->get_team_manager();
                                        echo $tm['name'];
                                    ?>
                                </p>
                            </div>
                        </div>
                        <div class="badge-wrapper">
                            <div class="badge-title">
                                <h4>Badges</h4>
                            </div>
                            <div class="badges">
                                <?php
                                    $badges = Badge::get_team($db, $team->get_id());

                                    if (empty($badges)) {
                                        echo '<p class="p-c">No badges on display</p>';
                                    } else {
                                        foreach ($badges as $i => $row) {
                                            echo '<span class="tooltiptext"><img src="'.$row['url'].'" width="100" height="100">'.$row['description'].'</span>';
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="school-logo">
                            <img src="<?php echo $team->get_logo(); ?>" alt="logo" width="100" height="100">
                        </div>
                    </div>
                    <div class="banner-bottom">
                        <div class="bio">
                            <?php if ($can_edit) { ?>
                                <textarea class="bio-text-edit e-c <?php echo $fill; ?>"></textarea>
                            <?php } ?>
                            <p class="bio-text p-c"></p>
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
                                        <table style="width:100%;" cellspacing="0">
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
                    <!--
                    <div class="stats-tab --tab" style="display:none;">
                        <div class="tab">
                            <div class="row">
                                
                            </div>
                        </div>
                    </div> -->
                    <div class="store-tab --tab" style="display:none;">
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