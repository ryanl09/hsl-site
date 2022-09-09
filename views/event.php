<?php

$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path . '/classes/event/Schedule.php');

require_once($path . '/classes/event/Event.php');
require_once($path . '/documentelements.php');

require_once($path . '/classes/general/Stats.php');


$event_id = count($_SESSION['current_page']) > 2 ? $_SESSION['current_page'][2] : 0;
$e = Event::exists($event_id);



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
        <section class="home loading c-auto">
            <div class="page-content">
                <input type="hidden" id="csrf" value="<?php echo $_SESSION['csrf']; ?>">
                <?php if ($e) { ?>
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
                <?php } ?>
            </div>

            <?php if ($_SESSION['user']->is_admin()) { ?>
                <button class="save-stats stats clickable"><i class='bx bx-save'></i>Save</button>
            <?php } ?>

        </section>

        <?php ui_script(); ?>

    </body>
</html>