<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');
require_once($path . '/classes/general/Game.php');

$redir=false;
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];

    if (!$user->is_admin()) {
        $redir=true;
    }
} else {
    $redir=true;
}

if ($redir){
    header('Location: /dashboard');
}

start_content_full(1, 'graphics'); ?>

<div class='img-data'>
    <h3>Image Data</h3>
    <div class="inputs">
        <div class="ssel">
            <label for="season">Season</label>
            <select name="season" id="season">
                <?php
                    require_once($path . '/classes/general/Season.php');
                    $s = Season::get_all_prior($db);

                    foreach ($s as $i => $row){
                        echo '<option value="'.$row['id'].'">';
                        echo $row['season_name'];
                        echo '</option>';
                    }
                ?>
            </select>
        </div>
        <div class="gsel">
            <label for="game">Game</label>
            <select name="games" id="games">
                <?php
                    $games = Game::get_all($db);

                    foreach ($games as $i => $row){
                        echo '<option value="'.$row['id'].'">';
                        echo $row['game_name'];
                        echo '</option>';
                    }
                ?>
            </select>
        </div>
        <div class="divsel">
            <label for="div">Divison</label>
            <select name="div" id="div">
                <option value="1">D1</option>
                <option value="2">D2</option>
            </select>
        </div>
        <div class="dsel">
            <label for="data">Data</label>
            <select name="data" id="data">
                <option value="1" selected>Matches</option>
                <option value="2">Standings</option>
                <option value="3">Final Scores</option>
                <option value="4">Rosters</option>
            </select>
        </div>
    </div>
</div>
<canvas width="1920" height="1080" id="canv" class="select-mode"></canvas>
<div class="btns">
    <button class="btn btn-save"><i class='bx bx-save'></i>Save</button>
    <button class="btn btn-undo"><i class='bx bx-undo'></i>Undo</button>
    <button class="btn btn-clear"><i class='bx bx-trash' ></i>Clear</button>
    <button class="btn btn-adddata"><i class='bx bxs-add-to-queue' ></i>Add data</button>
</div>
<div class="bx-pts-wrap">
    <table>
        <thead>
            <tr>
                <th>X</th>
                <th>Y</th>
                <th>Width</th>
                <th>Height</th>
            </tr>
        </thead>
        <tbody class="bx-pts"></tbody>
    </table>
</div>

<div class="footer-bar">
    <div class="add-image">
        <button id="upload-image"><i class='bx bx-image-add'></i></button>
        <input type="file" class="upload-img" style="display:none;">
    </div>
    <div class="img-list">
        <?php
        /*
            $dir = $path . '/images/graphics';
            $imgs = glob($dir . '/*.*');
            foreach($imgs as $img){
                $url = str_replace('/var/www/html/','https://tecesports.com/',$img);
                
                echo '<div class="img-box">';
                echo '<img src="'.$url.'">';
                echo '</div>';
            }
            */

            require_once($path . '/classes/graphics/GImage.php');

            $imgs = GImage::display_all($db);

            foreach ($imgs as $i => $row){
                echo '<div class="img-box" upload-id="'.$row['id'].'">';
                echo '<img src="https://tecesports.com' . $row['url'] . '">';
                echo '</div>';
            }
        ?>
    </div>
</div>

<?php end_content_full(1); ?>