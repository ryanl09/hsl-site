<?php

$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path . '/classes/event/Schedule.php');
include_once($path . '/classes/general/Game.php');

require_once($path . '/documentelements.php');

admin_block();

start_content_full(1, 'eventpanel'); ?>

<h2>Event Panel</h2>
<h4>Schedule Builder</h4>
<input type="checkbox" id="clear-fields" checked="checked">
<label for="clear-fields">Clear fields on game change</label>
<div class="blocks">
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
                            $games = Game::get_all($db);
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
                        <option value="2">2</option>
                    </select>
                </div>
            </div>
            <h4>Teams</h4>
            <div class="teamlist">
                <p id="no-team-selected">Select a game</p>
            </div>
        </div>
        <div class="mid-gen">
            <div class="listview">
                <div class="listview-header1">
                    <h4>Number of weeks:</h4>
                    <input type="text" id="numweeks">
                </div>
                <hr class="sep">
                <div class="listview-header">
                    <h4>Select days</h4>
                    <div class="day-cbox">
                        <input type="checkbox" name="day" value="monday" id="mon">
                        <label for="mon">Mon</label>
                    </div>
                    <div class="day-cbox">
                        <input type="checkbox" name="day" value="tuesday" id="tues">
                        <label for="tues">Tues</label>
                    </div>
                    <div class="day-cbox">
                        <input type="checkbox" name="day" value="wednesday" id="wed">
                        <label for="wed">Wed</label>
                    </div>
                    <div class="day-cbox">
                        <input type="checkbox" name="day" value="thursday" id="thu">
                        <label for="thu">Thu</label>
                    </div>
                    <div class="day-cbox">
                        <input type="checkbox" name="day" value="friday" id="fri">
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
                <hr class="sep">
                <div class="listview-footer">
                    <button class="btn-generate green clickable">Generate</button>
                </div>
            </div>
        </div>
        <div class="post-gen">
            <table cellspacing="0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Home</th>
                        <th>Away</th>
                    </tr>
                </thead>
                <tbody id="schedule-body">
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php end_content_full(1) ?>