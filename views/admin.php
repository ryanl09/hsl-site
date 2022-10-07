<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');
require_once($path . '/classes/util/tecdb.php');

admin_block();

?>

<?php start_content_full(1, 'admin'); ?>

<a href="/eventpanel">Event panel</a>
<br>
<a href="/graphics">Graphics</a>
<br>
<a href="/reg/hs">HS Registrations</a>
<br>
<a href="/reg/ymca">YMCA Registrations</a>
<br>
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

<?php end_content_full(1); ?>