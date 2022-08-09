<?php

$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path . '/classes/general/Game.php');

require_once($path . '/documentelements.php');

if (!isset($_SESSION['user']) || (isset($_SESSION['user']) && $_SESSION['user']->get_role() !== 'admin')) {
    header('Location: ' . href('dashboard'));
}
?>

<html>
    <?php 
    base_header(array(
        'styles' => ['eventpanel'],
        'scripts' => ['eventpanel']
        )
    ); 
    ?>
    <body>
        <?php print_navbar();?>
        <section class="home">
            <div class="page-content">
                <input type="hidden" id="csrf" value="<?php echo $_SESSION['csrf']; ?>">
                <h2>Event Panel</h2>
                <h4>Schedule Builder</h4>
                <input type="checkbox" id="clear-fields" checked="checked">
                <label for="clear-fields">Clear fields on game change</label>
                <div class="blocks">
                    <div class="pre-gen">
                        <div class="input-group">
                            <div class="input">
                                <label for="start-date">Start day:</label>
                                <input type="date" id="start-date">
                            </div>
                        
                            <div class="input">
                                <label for="games">Game:</label>
                                <select name="game" id="games">
                                    <?php
                                        $games = Game::get_all();
                                        foreach ($games as $i => $row) {
                                            echo '<option value="'.$row['id'].'">'.$row['game_name'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>

                            <div class="input">
                                <label for="div">Division</label>
                                <select name="div" id="div">
                                    <option value="1">1</option>
                                    <option value="1">2</option>
                                </select>
                            </div>
                        </div>
                        <div class="teamlist">
                            <p id="no-team-selected">Select a game</p>
                        </div>
                    </div>
                    <div class="mid-gen">
                        <div class="listview">
                            <div class="listview-header">
                                <h4>Select days</h4>
                                <div class="day-cbox">
                                    <input type="checkbox" name="day" value="mon" id="mon">
                                    <label for="mon">Mon</label>
                                </div>
                                <div class="day-cbox">
                                    <input type="checkbox" name="day" value="tues" id="tues">
                                    <label for="tues">Tues</label>
                                </div>
                                <div class="day-cbox">
                                    <input type="checkbox" name="day" value="wed" id="wed">
                                    <label for="wed">Wed</label>
                                </div>
                                <div class="day-cbox">
                                    <input type="checkbox" name="day" value="thu" id="thu">
                                    <label for="thu">Thu</label>
                                </div>
                                <div class="day-cbox">
                                    <input type="checkbox" name="day" value="fri" id="fri">
                                    <label for="fri">Fri</label>
                                </div>
                            </div>
                            <hr class="sep">
                            <div class="listview-body">
                                <div class="time-header">
                                    <h4>Select times</h4>
                                    <i class='bx bxs-plus-square clickable' id="add-time"></i>
                                </div>
                                <div class="game-times">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="post-gen">

                    </div>
                </div>
            </div>
        </section>
    </body>
</html>