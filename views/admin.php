<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');
require_once($path . '/classes/util/tecdb.php');

admin_block();

?>

<?php start_content_full(1, 'admin'); ?>

<div class="profile-tabs">
    <button class="tab-change selected" tab-id="1">Links</button>
    <button class="tab-change" tab-id="2">General</button>
</div>

<div class="tab" tab--id="1">
    <div class="row e3">
        <div class="box sm">
            <div class="box-title">
                <h3>Schedule</h3>
                <i class="bx bx-list-ol"></i>
            </div>
            <div class="btn-cont">
                <a href="/eventpanel">
                    <button class="btn smooth view-schedule">
                        <span>Create</span>
                        <i class="bx bx-chevron-right"></i>
                    </button>
                </a>
            </div>
        </div>
        <div class="box sm">
            <div class="box-title">
                <h3>Graphics</h3>
                <i class='bx bx-image-alt'></i>
            </div>
            <div class="btn-cont">
                <a href="/graphics">
                    <button class="btn smooth view-graphics">
                        <span>Create</span>
                        <i class="bx bx-chevron-right"></i>
                    </button>
                </a>
            </div>
        </div>
        <div class="box sm">
            <div class="box-title">
                <h3>Registrations</h3>
                <i class="bx bxs-school"></i>
            </div>
            <div class="btn-cont">
                <a href="/reg/hs">
                    <button class="btn smooth view-reg-hs">
                        <span>High School</span>
                        <i class="bx bx-chevron-right"></i>
                    </button>
                </a>
                <a href="/reg/ymca">
                    <button class="btn smooth view-reg-ymca">
                        <span>YMCA</span>
                        <i class="bx bx-chevron-right"></i>
                    </button>
                </a>
            </div>
        </div>
    </div>
</div>

<?php

/* //event csv
$query = 
"SELECT events.event_date as d, events.event_time as t, t.team_name as home, t2.team_name as away, events.event_stream, s.tag as st, s2.tag as st2
FROM events
INNER JOIN subteams s
    ON s.id = events.event_home
INNER JOIN teams t
    ON t.id = s.team_id
INNER JOIN subteams s2
    ON s2.id = events.event_away
INNER JOIN teams t2
    ON t2.id = s2.team_id
WHERE events.event_game = 3";

$d = new tecdb();

$res = $d->query($query)->fetchAll();

echo 'Date,Time,Home,Away,Stream<br>';

foreach ($res as $i => $row){
    echo $row['d'] . ',' . date('g:i a', strtotime($row['t'])) . ',' . $row['home'] . ' ' . $row['st'] . ',' . $row['away'] . ' ' . $row['st2'] .  ',' . $row['event_stream'] . '<br>';
}*/

?>
<div class="tab" tab--id="2" style="display:none;">
    <div class="create-box">
        <div class="create-pl">
            <div class="input-container">
                <input type="text" name="ign" id="ign" placeholder="ign">
                <select name="team" id="team">
                    <?php
                        $db = new tecdb();
                        $query = 
                        "SELECT id, team_name
                        FROM `teams`
                        WHERE `id` NOT IN (1, 2, 3, 24, 25, 26)
                        AND `team_type` = \"hs\"";

                        $res = $db->query($query)->fetchAll();

                        foreach ($res as $i => $row){
                            echo '<option value="'.$row['id'].'">'.$row['team_name'].'</option>';
                        }
                    ?>
                </select>
                <button class="btn btn-create"><i class='bx bxs-plus-circle'></i>Create</button>
            </div>
        </div>
        <div class="assign-pl">
            <div class="input-container">
                <input type="number" name="pl-id" id="pl-id">
                <select name="game" id="game">
                    <option value="1">Rocket League</option>
                    <option value="2">Valorant</option>
                    <option value="3">Overwatch 2</option>
                    <option value="4">League of Legends</option>
                </select>
                <select name="div" id="div">
                    <option value="1">Division 1</option>
                    <option value="2">Division 2</option>
                </select>
                <button class="btn btn-assign"><i class='bx bxs-add-to-queue'></i>Assign</button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="box post-announce">
            <h3>Make Announcement</h3>
            <input type="text" name="a-title" id="a-title">
            <textarea name="a-body" id="a-body" cols="30" rows="10"></textarea>
            <button class="btn save post-a"><i class='bx bx-edit' ></i>Post</button>
        </div>
    </div>

    <div class="del-ann-btns">
        <button class="btn del-ann-btn"><i class='bx bxs-flag-alt'></i>Delete An Announcement</button>
    </div>

    <div class="announcement-box-wrapper hide-box">
        <div class="announcement-box">
            <div class="announcement-header">
                <h3>Delete Announcement</h3>
            </div>
            <div class="announcement-body">
                    
                <div class="row announce">


                    <div class="box ann-box">
                        <div class="ann">
                            <h2 class="ann-title"></h2>
                                <div class="ann-author">
                                    <p class="author"></p>
                                </div>
                                <p class="ann-time"></p>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>

<?php end_content_full(1); ?>