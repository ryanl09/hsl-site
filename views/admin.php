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
    <button class="tab-change" tab-id="3">CSV</button>
    <button class="tab-change" tab-id="4">Analytics</button>
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

<div class="tab" tab--id="2" style="display:none;">
    <div class="create-box">
        <div class="create-pl">
            <div class="input-container">
                <input type="text" name="ign" id="ign" placeholder="ign">
                <select name="team" id="team">
                    <?php

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
        </div>
    </div>
</div>

<div class="tab" tab--id="3" style="display:none;">
    <button class="btn dl-players"><i class='bx bx-download'></i>Players</button>
</div>

<?php end_content_full(1); ?>