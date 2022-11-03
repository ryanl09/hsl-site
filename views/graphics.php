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
    </div>
</div>
<canvas width="1920" height="1080" id="canv" class="select-mode"></canvas>
<div class="btns">
    <button></button>
</div>

<div class="footer-bar">
    <div class="add-image">
        <button id="upload-image"><i class='bx bx-image-add'></i></button>
        <input type="file" id="image-upload" style="display:none;">
    </div>
    <div class="img-list">
        <?php
            $dir = $path . '/images/graphics';
            $imgs = glob($dir . '/*.*');
            foreach($imgs as $img){
                $url = str_replace('/var/www/html/','https://tecesports.com/',$img);
                
                echo '<div class="img-box">';
                echo '<img src="'.$url.'">';
                echo '</div>';
            }
        ?>
    </div>
</div>

<?php end_content_full(1); ?>