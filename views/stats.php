<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');
require_once($path . '/classes/general/Game.php');
require_once($path . '/classes/util/tecdb.php');

start_content_full(1, 'stats');

$games = Game::get_all($db);

$game_icons='<div class="game-icons e-game">';
foreach ($games as $i => $row){
    $sel='';
    if (!$i){
        $sel=' selected';
    }
    $game_icons .= '<img class="game-icon'.$sel.'" game-id="'.$row['id'].'" src="'.$row['url'].'" width="35" height="35">';
}
$game_icons.='</div>';


?>

<h3 style="margin-bottom:10px;">Stats</h3>
<div class="row e2thirds">
    <div class="box stats-box">
        <div class="stats-top">
            <h3>Sort by</h3>
            <?php echo $game_icons; ?>
        </div>
        <div class="sort-by">
            <select name="team" id="team">
                <option value="-1" selected style="display:none;">Any team</option>
            </select>
            <select name="div" id="div">
                <option value="1">Division 1</option>
                <option value="2">Division 2</option>
            </select>
            <select name="season" id="season">
                <option selected>Fall 2022 (Current)</option>
            </select>
        </div>
        <hr class="sep">
        <div>
            <table cellspacing="0">
                <thead class="stats-thead"></thead>
                <tbody class="stats-tbody"></tbody>
            </table>
        </div>
    </div>
    <div class="panel">
        <div class="box">
            <h3>Top players of the week</h3>
            <select name="top-stat" id="top-stat"></select>
            <table>
                <thead>
                    <tr>
                        <th>IGN</th>
                        <th>Total</th>
                        <th>Team</th>
                    </tr>
                </thead>
                <tbody class="top-tbody"></tbody>
            </table>
        </div>
    </div>
</div>

<?php end_content_full(1); ?>