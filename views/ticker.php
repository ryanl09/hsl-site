<?php

$path = $_SERVER['DOCUMENT_ROOT'];

require_once($path . '/documentelements.php');

require_once($path . '/classes/event/Event.php');

$today = Event::all_today();

print_r($today);

$e = Event::exists(1);

echo $e->get_time();

$home_team = $e->get_home_team();
$away_team = $e->get_away_team();

echo 'HOME: ' . $home_team['event_home'] . PHP_EOL;
echo 'AWAY: ' . $away_team['event_away'] . PHP_EOL;





?>

<html>
    <?php 
    base_header(array(
        'styles' => ['ticker'],
        'scripts' => ['ticker']
    ), true
    ); 
    ?>
    <body>
    <input type="hidden" id="csrf" value="<?php echo $_SESSION['csrf']; ?>">
    <div class="ticker-wrapper">
        <ul class="overlap">
            <li class="img">
                <div>
                    <img src="../images/logo.png" width="60" height="40" alt="">
                </div>
            </li>
            <li class="ticker"></li>
            <li class="ticker2"></li>
        </ul>
    </div>

    <?php /*
    <div class="ticker-wrapper">
        <div class="ticker">
            <?php echo 'HOME: ' . $home_team['event_home'] . PHP_EOL; ?>
        </div>
        <div class="ticker2">
            <?php echo 'AWAY: ' . $away_team['event_away'] . PHP_EOL; ?>
        </div>
    </div>
*/
    ?>

    </body>
</html>