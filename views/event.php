<?php

$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path . '/classes/event/Schedule.php');
include_once($path . '/classes/general/Game.php');
include_once($path . '/classes/general/Stats.php');

require_once($path . '/classes/event/Event.php');
require_once($path . '/classes/Team/SubTeam.php');
require_once($path . '/documentelements.php');

$event_id = count($_SESSION['current_page']) > 2 ? $_SESSION['current_page'][2] : 0;
$e = Event::exists($event_id);
$home=0;
$away=0;

$s = new Stats();
$cols = $s->get_cols($event_id);


$size = 'width="220" height="220"';

if ($e) {
    $home = $e->get_home_team();
    $away = $e->get_away_team();
}
$h = new SubTeam($home['event_home']);
$a = new SubTeam($away['event_away']);

print_r($s->get_stats($event_id, $a->get_id()));

?>

<html>
    <?php 
    base_header(array(
        'styles' => ['event'],
        'scripts' => ['event']
        )
    ); 
    ?>
    <body>
        <?php print_navbar();?>
        <section class="home">
            <div class="page-content">
                <input type="hidden" id="csrf" value="<?php echo $_SESSION['csrf']; ?>">
                <?php if ($e) { ?>
                    <div class="match-header">
                        <div class="home-team">
                            <img src="<?php echo $home['team_logo']; ?>" <?php echo $size; ?> alt="">
                            <p><?php echo $home['team_name']; ?></p>
                        </div>
                        <div class="vs"><p>vs</p></div>
                        <div class="away-team">
                            <img src="<?php echo $away['team_logo']; ?>" <?php echo $size; ?> alt="">
                            <p><?php echo $away['team_name']; ?></p>
                        </div>
                    </div>

                    <div class="info-container">
                        <div class="info-box">
                            <div class="table-box">
                                <table>
                                    <thead id="thead-home">
                                        <th>Player</th>
                                        <?php 
                                            foreach ($cols as $i => $r) {
                                                echo '<th>'.$r['name'].'</th>';
                                            }
                                        ?>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="info-box">
                            <div class="table-box">
                                <table>
                                    <thead id="thead-home">
                                        <th>Player</th>
                                        <?php 
                                            foreach ($cols as $i => $r) {
                                                echo '<th>'.$r['name'].'</th>';
                                            }
                                        ?>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                        <!-- upcoming matches -->
                <?php } ?>
            </div>
        </section>

        <?php ui_script(); ?>

    </body>
</html>