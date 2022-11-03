<?php

$path = $_SERVER['DOCUMENT_ROOT'];

require_once($path . '/documentelements.php');
require_once($path . '/classes/event/Event.php');

$today = Event::all_today($db);

print_r($today);

$e = Event::exists($db, 1);

if ($e){
    echo $e->get_time();
    
    $home_team = $e->get_home_team();
    $away_team = $e->get_away_team();
    
    echo 'HOME: ' . $home_team['event_home'] . PHP_EOL;
    echo 'AWAY: ' . $away_team['event_away'] . PHP_EOL;
}

start_content_full(0, 'ticker'); ?>

<div class="ticker-wrapper">
    <ul class="overlap">
        <li class="img">
            <div>
                <img src="../images/logo.png" width="60" height="40" alt="">
            </div>
        </li>
        <li class="progress"></li>
        <li class="ticker"></li>
        <li class="ticker2"></li>
    </ul>
</div>

<?php end_content_full(0); ?>