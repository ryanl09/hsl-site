<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');
require_once($path . '/classes/event/Event.php');
require_once($path . '/classes/general/Game.php');

start_content_full(1, 'events');

echo '<script src="https://embed.twitch.tv/embed/v1.js"></script>';
    
// latest matchup
//weekly matchup
//breakdown by games
//sort search


$games = Game::get_all($db);

$game_icons='<div class="game-icons e-today">';
$game_icons2='<div class="game-icons e-all">';
$game_icons_calendar='<div class="game-icons e-all-calendar">';

foreach ($games as $i => $row){
    $sel='';
    if (!$i){
        $sel=' selected';
    }
    $game_icons .= '<img class="game-icon'.$sel.'" game-id="'.$row['id'].'" src="'.$row['url'].'" width="35" height="35">';
    $game_icons2 .= '<img class="game-icon'.$sel.'" game-id="'.$row['id'].'" src="'.$row['url'].'" width="35" height="35">';
    $game_icons_calendar .= '<img class="game-icon-calendar'.$sel.'" game-id-calendar="'.$row['id'].'" src="'.$row['url'].'" width="35" height="35">';
}
$game_icons.='</div>';
$game_icons2.='</div>';
$game_icons_calendar.='</div>';
?>

<div class="top-section">
    <h2>Events</h2>
    <div class="display-opts">
        <button class="default-view selected"><i class='bx bx-checkbox'></i></button>
        <button class="calendar-view"><i class='bx bxs-calendar' ></i></button>
    </div>
</div>

<div class="mid-section">
    <div class="row e2thirds">
        <div class="box events-today">
            <div class="today-top">
                <h3>Today</h3>
                <?php echo $game_icons; ?>
            </div>
            <hr class="sep">
            <p class="today-nogames">No games today!</p>
            <div class="table-today">
                <table cellspacing="0">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Home</th>
                            <th>Away</th>
                            <th>Division</th>
                            <th>Result</th>
                            <th>Stream</th>
                        </tr>
                    </thead>
                    <tbody class="table-today-tbody">
                    </tbody>
                </table>
            </div>
        </div>
        <div class="box current-event" id="current-ev" style="padding:0px !important">
            <!--
            <div class="ce-header">
                <h2>Happening now</h2>
            </div>
            <div class="matchup">
                <img src="https://tecesports.com/images/tec-black.png" width="80" height="80" alt="">
                <h2>VS</h2>
                <img src="https://tecesports.com/images/tec-black.png" width="80" height="80" alt="">
            </div>
            <button class="watch-now">Watch live!</button>-->
        </div>
        <script type="text/javascript">
        var embed = new Twitch.Embed("current-ev", {
        channel: "theesportcompany",
        parent: ["tecesports.com"]
        });

        embed.addEventListener(Twitch.Embed.VIDEO_READY, function() {
            $('.ce-te__chat').remove();
        });
        </script>
    </div>
    <div class="row e1">
        <div class="box all-events">
            <div class="all-header">
                <div class="today-top">
                    <h3>All events</h3>
                    <?php echo $game_icons2; ?>
                </div>
                <div class="sort-by">
                    <span>Sort By:</span>
                    <select name="sort-team" id="sort-team">
                        <option value="-1" selected>Any Team</option>
                    </select>
                    <select name="sort-div" id="sort-div">
                        <option value="1">D1</option>
                        <option value="2">D2</option>
                    </select>
                    <select name="sort-season" id="sort-season">
                        <option value="-1" selected>Current Season</option>
                    </select>
                    <select name="sort-time" id="sort-time">
                        <option value="all" selected>All</option>
                        <option value="upcoming">Upcoming</option>
                        <option value="past">Past</option>
                    </select>
                </div>
            </div>
            <hr class="sep">
            <div class="all-table">
                <table cellspacing="0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Home</th>
                            <th>Away</th>
                            <th>Division</th>
                            <th>Result</th>
                            <th>Stream</th>
                        </tr>
                    </thead>
                    <tbody class="table-all-tbody">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="mid-section2" style="display: none">
    <?php 
        require_once($path . '/classes/event/Calendar.php');
        $calendar = new Calendar();
    ?>
    <body>
        <div class="content">
            <div class="box all-events-calendar">
                <div class="all-header">
                    <div class="today-top">
                        <h3>All events</h3>
                        <?php echo $game_icons_calendar; ?>
                    </div>
                    <div class="sort-by-calendar">
                        <span>Sort By:</span>
                        <select name="sort-team-calendar" id="sort-team-calendar">
                            <option value="-1" selected>Any Team</option>
                        </select>
                        <select name="sort-div-calendar" id="sort-div-calendar">
                            <option value="1">D1</option>
                            <option value="2">D2</option>
                        </select>
                    </div>
                </div>
            </div>
            <?php echo $calendar->show(); ?>
        </div>
    </body>
</div>


<?php end_content_full(1); ?>