<?php

$path = $_SERVER['DOCUMENT_ROOT'];

require_once($path . '/ajax/ajaxdb.php');
include_once($path . '/classes/event/Schedule.php');
require_once($path . '/classes/event/Event.php');
require_once($path . '/classes/general/Stats.php');
require_once($path . '/classes/team/SubTeam.php');
require_once($path . '/documentelements.php');

$db = ajaxdb::get_instance();

$event_id = count($_SESSION['current_page']) > 2 ? $_SESSION['current_page'][2] : 0;
$e = Event::exists($db, $event_id);
start_content_full(1, 'event', 'loading c-auto');

$admin = $_SESSION['user'] && $_SESSION['user']->is_admin();

if ($e) { ?>


    <div class="flag-box-wrapper hide-box">
        <div class="flag-box">
            <div class="flag-header">
                <h3>Set event flag</h3>
            </div>
            <div class="flag-body">
                <div class="inputs">
                    <select name="flagtype" id="flagtype">
                        <option value="Forfeit">Forfeit</option>
                        <option value="Reschedule">Reschedule</option>
                    </select>
                    <input type="text" name="reason" id="reason" placeholder="Reason">
                </div>
            </div>
            <div class="flag-footer">
                <button class="save-btn set-flag"><i class='bx bx-save'></i>Save</button>
            </div>
        </div>
    </div>

    <div class="event-image">
        <img src="<?php echo Event::game_image($db, $event_id); ?>" width="50" height="50" alt="">
    </div>

    <div class="match-header show-onload" style="display:none;">
        <div class="home-team"></div>
        <div class="vs"><p>vs</p></div>
        <div class="away-team"></div>
    </div>

    <div class="info-container show-onload" style="display:none;">
        <div class="info-box">
            <?php
                if ($admin){
                    echo '<div class="add-player-admin">';
                    $get_home_team = $e->get_home_team();
                    $h_id = $get_home_team['t_id'];
                    echo '<input type="hidden" id="home-team-id" value="'.$h_id.'">';
                    echo '<select id="temp-home">';
                    $home = new SubTeam($db, $h_id);
                    $h_pl = $home->get_players(true);

                    foreach ($h_pl as $i => $row){
                        echo '<option value="'.$row['user_id'].'">';
                        $ign = User::get_ign_with_id($row['user_id'], $e->get_game_id());
                        echo $ign;
                        echo '</option>';
                    }
                    echo '</select>';
                    echo '<button class="btn btn-add-home"><i class="bx bxs-plus-circle"></i>Add</button>';
                    echo '<div class="score">';
                    echo '<label for="home-score">Final score:</label>';
                    echo '<input type="number" name="home-score" id="home-score">';
                    echo '</div>';
                    echo '</div>';
                }
            ?>
            <div class="table-box">
                <table>
                    <thead id="thead-home"></thead>
                    <tbody id="tbody-home"></tbody>
                </table>
            </div>
        </div>
        <div class="info-box">
            <?php
                if ($admin){
                    echo '<div class="add-player-admin">';
                    $get_away_team = $e->get_away_team();
                    $a_id = $get_away_team['t_id'];
                    echo '<input type="hidden" id="away-team-id" value="'.$a_id.'">';
                    echo '<select id="temp-away">';
                    $away = new SubTeam($db, $a_id);
                    $a_pl = $away->get_players(true);

                    foreach ($a_pl as $i => $row){
                        echo '<option value="'.$row['user_id'].'">';
                        $ign = User::get_ign_with_id($row['user_id'], $e->get_game_id());
                        echo $ign;
                        echo '</option>';
                    }
                    echo '</select>';
                    echo '<button class="btn btn-add-away"><i class="bx bxs-plus-circle"></i>Add</button>';
                    echo '<div class="score">';
                    echo '<label for="away-score">Final score:</label>';
                    echo '<input type="number" name="away-score" id="away-score">';
                    echo '</div>';
                    echo '</div>';
                }
            ?>
            <div class="table-box">
                <table>
                    <thead id="thead-away"></thead>
                    <tbody id="tbody-away"></tbody>
                </table>
            </div>
        </div>
    </div>

    <?php 
        if (isset($_SESSION['user']) && $_SESSION['user']->is_admin()) { ?>
        <div class="admin-btns">
            <button class="save-btn stats"><i class='bx bx-save'></i>Save</button>
            <button class="btn flag-btn"><i class='bx bxs-flag-alt'></i></button>
        </div>
    <?php } 
 } else { ?>
        <!-- upcoming matches -->
<?php }

end_content_full(1); ?>