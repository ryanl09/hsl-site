<?php
/*
$db = new tecdb();

$query=
"SELECT events.event_date, events.event_time, teams.team_name, events.event_stream
FROM `events`
INNER JOIN subteams
    ON subteams.id = events.event_home OR subteams.id = events.event_away
INNER JOIN `teams`
    ON subteams.team_id = teams.id
WHERE events.event_game=2  AND events.event_stream=\"https://www.twitch.tv/techighschoolgroup\"
ORDER BY events.event_date ASC";

$csv = "Date,Time,Home,Away,Stream\n";

$row = $db->query($query)->fetchAll();

for ($j = 0; $j < count($row); $j += 2){
    $csv .= $row[$j]['event_date'] . ',' . $row[$j]['event_time'] . ',' . $row[$j]['team_name'] . ','
        . $row[($j+1)]['team_name'] . ',' . $row[$j]['event_stream'] ."\n";
}*/
?>

<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');
require_once($path . '/classes/event/Event.php');
require_once($path . '/classes/general/Game.php');
require_once($path . '/classes/util/tecdb.php');

start_content_full(1, 'events');

// latest matchup
//weekly matchup
//breakdown by games
//sort search


$games = Game::get_all();

$game_icons='<div class="game-icons">';
foreach ($games as $i => $row){
    $sel='';
    if (!$i){
        $sel=' selected';
    }
    $game_icons .= '<img class="game-icon'.$sel.'" game-id="'.$row['id'].'" src="'.$row['url'].'" width="35" height="35">';
}
$game_icons.='</div>';


$today = Event::all_today_game(1);
$p_hide = '';
$t_hide = '';

if (empty($today)){
    $t_hide=' style="display:none;"';
}else{
    $p_hide=' style="display:none;"';
}


?>

<div class="top-section">
    <h2>Events</h2>
</div>

<div class="mid-section">
    <div class="row e2thirds">
        <div class="box events-today">
            <div class="today-top">
                <h3>Today</h3>
                <?php echo $game_icons; ?>
            </div>
            <hr class="sep">
            <p class="today-nogames"<?php echo $p_hide; ?>>No games today!</p>
            <div class="table-today"<?php echo $t_hide; ?>>
                <table>
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Home</th>
                            <th>Away</th>
                            <th>Division</th>
                            <th>Stream</th>
                        </tr>
                    </thead>
                    <tbody class="table-today-tbody">

                    </tbody>
                </table>
            </div>
        </div>
        <div class="box current-event">
            <div class="ce-header">
                <h2>Happening now</h2>
            </div>
            <div class="matchup">
                <img src="https://tecesports.com/images/tec-black.png" width="80" height="80" alt="">
                <h2>VS</h2>
                <img src="https://tecesports.com/images/tec-black.png" width="80" height="80" alt="">
            </div>
            <button>Watch live!</button>
        </div>
    </div>
</div>


<?php end_content_full(1); ?>