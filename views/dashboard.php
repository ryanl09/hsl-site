<?php


$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');
require_once($path . '/classes/general/Game.php');
require_once($path . '/classes/user/User.php');
require_once($path . '/classes/util/Sessions.php');
require_once($path . '/classes/team/Team.php');

require_once('redirect_login.php');

$role='';
if (isset($_SESSION['user'])){
    $role=$_SESSION['user']->get_role();
    if ($role==='admin'&&count($_SESSION['current_page'])>2&&$_SESSION['current_page'][2]) {
        $role=$_SESSION['current_page'][2];
    }    
}

start_content_full(1, 'dashboard');

switch ($role) {
    case 'player': ?>
        <div class="row e3">
            <div class="box tall">
                <h4>Matches</h4>
            </div>
        </div>
        <?php break;
    case 'team_manager': ?>
        <div class="row e3">
            <div class="box tall">
                <h3 class="box-title">Players</h3>
                <p>Students will register with this link:</p>
                <?php 
                    $team = new Team($_SESSION['user']->get_team_id());
                    $href = 'https://tecesports.com/register/' . $team->get_schoolcode();
                ?>
                <span style="display:flex; gap:10px; align-items:center;">
                    <div class="copy-code">
                        <i class='bx bx-copy'></i>
                    </div>
                    <a id="schoolcode" href="<?php echo $href;?>"><?php echo $href;?></a>
                </span>
                <h3 class="box-title">Registered Players</h3>
                <div class="rpw">
                    <table class="r-players" cellspacing="0">
                        <tbody>
                            <?php
                                $pl = $team->get_players();
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
                    $games = Game::get_all();
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
                        $cboxes .= '<div class="t-select"><input type="checkbox" id="'.$id.'"><label for="'.$id.'">'.$text.'</label></div>';
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
                    <button id="save-pl-t" class="save-btn clickable"><i class='bx bx-save'></i>Save</button>
                </div>
            </div>
            <div class="box tall s-events"></div>
        </div>
        <?php break;
    case 'caster': ?>

        <?php break;
    case 'college': ?>

        <?php break;
    case 'admin': ?>

        <?php break;
    default: ?>
        <?php break;
} 

end_content_full(1); ?>