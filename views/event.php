<?php

$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path . '/classes/event/Schedule.php');

require_once($path . '/classes/event/Event.php');
require_once($path . '/documentelements.php');

require_once($path . '/classes/general/Stats.php');

require_once($path . '/classes/team/SubTeam.php');

$event_id = count($_SESSION['current_page']) > 2 ? $_SESSION['current_page'][2] : 0;
$e = Event::exists($event_id);

start_content_full(1, 'event', 'loading c-auto');

print_r($s);

if ($e) { ?>
    <div class="event-image">
        <img src="<?php echo Event::game_image($event_id); ?>" width="50" height="50" alt="">
    </div>

    <div class="match-header show-onload" style="display:none;">
        <div class="home-team"></div>
        <div class="vs"><p>vs</p></div>
        <div class="away-team"></div>
    </div>

    <div class="info-container show-onload" style="display:none;">
        <div class="info-box">
            <div class="table-box">
                <table>
                    <thead id="thead-home"></thead>
                    <tbody id="tbody-home"></tbody>
                </table>
            </div>
        </div>
        <div class="info-box">
            <div class="table-box">
                <table>
                    <thead id="thead-away"></thead>
                    <tbody id="tbody-away"></tbody>
                </table>
            </div>
        </div>
    </div>

<?php } else { ?>
        <!-- upcoming matches -->
<?php } if (isset($_SESSION['user']) && $_SESSION['user']->is_admin()) { ?>
    <button class="save-btn stats clickable"><i class='bx bx-save'></i>Save</button>
<?php }

end_content_full(1); ?>