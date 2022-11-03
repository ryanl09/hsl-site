<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');
require_once($path . '/classes/event/Event.php');

start_content_full(1, 'league');


?>

<h2 style="margin-bottom:10px;">League</h2>

<div class="row e1">
    <div class="box playing-next">
        <h3>Playing Next</h3>
        <?php
            $n = Event::get_next($db);
            $h_logo = '<img src="'.$n['home_logo'].'" width="80" height="80" class="clickable">';
            $a_logo = '<img src="'.$n['away_logo'].'" width="80" height="80" class="clickable">';
            $time = strtoupper(date('g:i a', strtotime($n['event_time'])));
        ?>
        <div class="next-game">
            <img src="<?php echo $n['game_logo']; ?>" width="30" height="30">
        </div>
        <div class="next-matchup">
            <?php echo $h_logo; ?>
            <span>VS</span>
            <?php echo $a_logo; ?>
        </div>
        <div class="next-info">
            <p class="info-text" e-date="<?php echo $n['event_date']; ?>" e-time="<?php echo $n['event_time']; ?>"></p>
        </div>
        <div class="next-stream">
            <button class="watch-next"><i class='bx bxl-twitch' ></i></button>
        </div>
    </div>
</div>

<div class="row e3">
    <div class="box sm">
        <div class="box-title">
            <h3>Standings</h3>
            <i class='bx bx-list-ol' ></i>
        </div>
        <div class="btn-cont">
            <button class="btn smooth view-standings">
                <span>View all</span>
                <i class='bx bx-chevron-right' ></i>
            </button>
        </div>
    </div>
    <div class="box sm">
        <div class="box-title">
            <h3>Stats</h3>
            <i class='bx bx-stats' ></i>
        </div>
        <div class="btn-cont">
            <button class="btn smooth view-stats">
                <span>View all</span>
                <i class='bx bx-chevron-right' ></i>
            </button>
        </div>
    </div>
    <div class="box sm">
        <div class="box-title">
            <h3>Teams</h3>
            <i class='bx bxs-school' ></i>
        </div>
        <div class="btn-cont">
            <button class="btn smooth view-teams">
                <span>View all</span>
                <i class='bx bx-chevron-right' ></i>
            </button>
        </div>
    </div>
</div>

<?php end_content_full(1); ?>