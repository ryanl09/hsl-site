<script type="text/javascript" src="/js/dashboard-tm.js"></script>

<div class="profile-tabs">
    <button class="tab-change" tab-id="1" tab-target="home">Home</button>
    <button class="tab-change" tab-id="2" tab-target="players">Players</button>
    <button class="tab-change" tab-id="6" tab-target="rosters">Rosters</button>
    <button class="tab-change" tab-id="3" tab-target="announcements">Announcements</button>
    <!-- <button class="tab-change" tab-id="4">Rules</button> -->
    <button class="tab-change" tab-id="5" tab-target="fundraising">Fundraising</button>
</div>

<div class="tab" tab--id="1">
    <div class="opt-next">
        <?php
            require_once($path . '/classes/general/Season.php');
            $n = Season::get_next_name($db);

            $team_id = $_SESSION['user']->get_team_id();
            $t = new Team($db, $team_id);
            $checked = $t->is_applied_next() ? 'checked' : '';
        ?>
        <input type="checkbox" id="apply-n" <?php echo $checked; ?>>
        <label for="apply-n">Opt in for next season (<?php echo $n; ?>)</label>
    </div>
    <h3 class="sec-title">Blog posts<i class='bx bx-news' ></i></h3>
    <hr class="sep">
    <div class="row showloading">
        <div class="blog-posts">

        </div>
    </div>
</div>

<div class="tab" tab--id="2" style="display:none;">
    <p>Students will register with this link:</p>
    <?php 
        $team = new Team($db, $_SESSION['user']->get_team_id());
        $href = 'https://tecesports.com/register/' . $team->get_schoolcode();
    ?>
    <span style="display:flex; gap:10px; align-items:center;">
        <div class="copy-code">
            <i class='bx bx-copy'></i>
        </div>
        <a id="schoolcode" href="<?php echo $href;?>"><?php echo $href;?></a>
    </span>
    <div class="row e2">
        <div class="box tall">
            <h3 class="box-title">Players</h3>
            <div class="rpw">
                <table class="r-players" cellspacing="0">
                    <tbody>
                        <?php
                            $pl = $team->get_players(false);
                            foreach ($pl as $i => $row) {
                                echo '<tr>
                                        <td user-id="'.$row['user_id'].'" username="'.$row['username'].'" class="user-col">
                                            <span>'.$row['name'].'</span>
                                            <i class="bx bx-chevron-right"></i>
                                        </td>
                                    </tr>';
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="time-header" style="margin-top:16px;">
                <h3 class="box-title">Teams</h3>
                <i class="bx bxs-plus-square clickable m-add" id="add-team"></i>
            </div>
            <?php
                $st = $team->get_subteams_games();
                $cboxes='';
                $games = Game::get_all($db);
                echo '<div class="game-times">';
                $js_games=[]; //lazy
                $js_divs=[];

                for ($i= 0; $i< count($games); $i++){
                    $js_games[] = array(
                        'id' => $games[$i]['id'],
                        'name' => $games[$i]['game_name']
                    );
                }
                for ($j=0;$j<2;$j++){
                    $js_divs[]=array(
                        'id'=>$j+1,
                        'name' => 'D' . ($j+1)
                    );
                }

                foreach ($st as $i => $row) {
                    if(count($js_games)>0){
                        $can_push=false;
                    }
                    $text = $row['game_name'] . ' - Division ' . $row['division'];
                    $id = 'st-' . $row['id'];
                    $cboxes .= '<div class="t-select"><input class="add-team-opt" type="checkbox" id="'.$id.'"><label for="'.$id.'">'.$text.'</label></div>';
                    ?>

                    <div class="game-time" st-id="<?php echo $row['id']; ?>" id="subteam-<?php echo $i; ?>">
                        <select class="sel-gam" name="team-game" id="team-game">
                            <?php
                                for($i=0;$i<count($games);$i++){
                                    echo '<option value="'.$games[$i]['id'].'" '.($games[$i]['id']===$row['game_id']?'selected':'').'>'.$games[$i]['game_name'].'</option>';
                                    
                                }
                            ?>
                        </select>
                        <select class="sel-div" name="team-div" id="team-div">
                            <?php 
                                for ($i = 0; $i < 2; $i++){
                                    echo '<option value="'.($i+1).'" '.($i+1===$row['division']?'selected':'').'>D'.($i+1).'</option>';
                                }
                            ?>
                        </select>
                        <i class="bx bxs-checkbox-minus clickable"></i>
                    </div>
            <?php } echo '</div>
            <button class="save-btn save-teams clickable" style="display:none;"><i class="bx bx-save"></i>Save</button>
            '; 
            
            echo '<script type="text/javascript">
                const games = '.json_encode($js_games).'
                const divs = '.json_encode($js_divs).'
            </script>'
            ?>

        </div>

        <div class="box tall s-player">
            <h3 class="box-title">Allocate players</h3>
            <div class="empty-player">
                <p>Select a player</p>
            </div>
            <div class="player-info">
                <p id="p-name"></p>
                <p id="p-uname"></p>
            </div>
            <div class="set-teams">
                <h3 class="box-title">Select Teams</h3>
                <div class="subteams">
                    <?php echo $cboxes; ?>
                </div>
                <div class="pl-btns">
                    <button id="save-pl-t" class="save-btn clickable"><i class='bx bx-save'></i>Save</button>
                    <button id="p-delete" pl-id="udf"><i class='bx bxs-minus-circle'></i>Remove player</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="tab" tab--id="6" style="display:none;"><div class="row e2">
        <div class="box s-events tall">
            <h3 class="box-title">Set rosters</h3>
            <div class="split">
                <div class="split-top">
                    <div class="select-roster-team input">
                        <label for="roster-team">Team:</label>
                        <select id="roster-team">
                        <?php
                            $use = 0;
                            foreach ($st as $i => $row){
                                if(!$i){
                                    $use=$row['id'];
                                }
                                echo '<option value="'.$row['id'].'">'.$row['game_name'].' - D'.$row['division'].'</option>';
                            }
                        ?>
                        </select>
                    </div>
                        
                    <div class="events-table">
                        <table cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>VS</th>
                                </tr>
                            </thead>
                            <tbody class="events-tbody">
                                <?php
                                    $e = Event::of_subteam($db, $use);
                                    foreach ($e as $i => $row){
                                        echo '<tr class="tr-set" e-id="'.$row['e_id'].'" e-time="'.$row['event_time'].'" e-date="'.$row['event_date'].'">';
                                        $class = 'red';
                                        if (Event::has_roster($db, $row['e_id'], $use)){
                                            $class='green';
                                        }
                                        echo '<td><i class="bx bxs-circle '.$class.'"></i></td>';
                                        echo '<td>'.$row['event_date'].' @' . $row['event_time'] . '</td>';
                                        $op = $row['event_away'];
                                        if ($row['a_id']===$use){
                                            $op = $row['event_home'];
                                        }
                                        echo '<td>'.$op.'</td>';
                                        echo '</tr>';
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="box tall add-s">
            <h3 class="box-title">Set rosters</h3>
            <div class="avail-pl"></div>
        </div>
    </div>

</div>


<div class="tab" tab--id="3" style="display:none;">
    <div class="row announce"></div>
</div>


<div class="tab" tab--id="4" style="display:none;">
    <div class="row">
        <div class="box">
            <h3>Rules</h3>
        </div>
    </div>
</div>


<div class="tab" tab--id="5" style="display:none;">
    <div class="row">
        <div class="box">
            <h3>Fundraising</h3>
        </div>
    </div>
</div>