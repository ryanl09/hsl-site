<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');
require_once($path . '/classes/user/User.php');
require_once($path . '/classes/util/Sessions.php');
require_once($path . '/classes/team/Team.php');

require_once('redirect_login.php');

$role='';
if (isset($_SESSION['user'])){
    $role=$_SESSION['user']->get_role();
    if ($role==='admin'&&count($_SESSION['current_page'])>2&&$_SESSION['current_page'][2]) {
        $role=$_SESSION['current_page'][2];
    }    
}
?>


<html>
    <?php base_header(
        array(
        'styles' => ['dashboard'],
        'scripts' => ['dashboard']
        )
    ); ?>
    <body>
        <?php print_navbar();?>
        <section class="home">
            <div class="page-content">
                <?php 
                    switch ($role) {
                        case 'player': ?>
                            <div class="row e3">
                                <div class="box tall">
                                    <h4>Matches</h4>
                                </div>
                            </div>
                            <?php break;
                        case 'team_manager': ?>
                            <div class="row e3">
                                <div class="box tall">
                                    <h3 class="box-title">Players</h3>
                                    <p>Students will register with this link:</p>
                                    <?php 
                                        $team = new Team($_SESSION['user']->get_team_id());
                                        $href = 'https://tecesports.com/register/' . $team->get_schoolcode();
                                    ?>
                                    <span style="display:flex; gap:10px; align-items:center;">
                                        <div class="copy-code">
                                            <i class='bx bx-copy'></i>
                                        </div>
                                        <a id="schoolcode" href="<?php echo $href;?>"><?php echo $href;?></a>
                                    </span>
                                    <h3 class="box-title">Registered Players</h3>
                                    <div class="rpw">
                                        <table class="r-players" cellspacing="0">
                                            <tbody>
                                                <?php
                                                    $pl = $team->get_players();
                                                    foreach ($pl as $i => $row) {
                                                        echo '<tr>
                                                                <td user-id="'.$row['user_id'].'" username="'.$row['username'].'" class="user-col">
                                                                    <span>'.$row['name'].'</span>
                                                                    <i class="bx bx-chevron-right"></i>
                                                                </td>
                                                            </tr>';
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <h3 class="box-title">Teams</h3>
                                    <?php
                                        $st = $team->get_subteams_games();

                                        $cboxes='';
                                        foreach ($st as $i => $row) {
                                            $text = $row['game_name'] . ' - Division ' . $row['division'];
                                            echo '<p>'.$text.'</p>';

                                            //store for later so less loops
                                            $id = 'st-' . $row['id'];
                                            $cboxes .= '<div class="t-select"><input type="checkbox" id="'.$id.'"><label for="'.$id.'">'.$text.'</label></div>';
                                        }
                                    ?>
                                </div>
                                <div class="box tall s-player">
                                    <h3 class="box-title">Allocate players</h3>
                                    <div class="empty-player">
                                        <p>Select a player</p>
                                    </div>
                                    <div class="player-info">
                                        <p id="p-name"></p>
                                        <p id="p-uname"></p>
                                    </div>
                                    <div class="set-teams">
                                        <h3 class="box-title">Select Teams</h3>
                                        <div class="subteams">
                                            <?php echo $cboxes; ?>
                                        </div>
                                        <button class="save-btn clickable"><i class='bx bx-save'></i>Save</button>
                                    </div>
                                </div>
                                <div class="box tall s-events"></div>
                            </div>
                            <?php break;
                        case 'caster': ?>

                            <?php break;
                        case 'college': ?>

                            <?php break;
                        case 'admin': ?>

                            <?php break;
                        default: ?>
                            <?php break;
                    } ?>
            </div>
        </section>

        <?php ui_script() ?>

    </body>
</html>