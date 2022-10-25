<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');

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

<canvas width="1920" height="1080" id="canv"></canvas>

<div class="footer-bar">
    <div class="add-image">
        <button id="upload-image"><i class='bx bx-image-add'></i></button>
        <input type="file" id="image-upload" style="display:none;">
    </div>
    <div class="img-list">
        <?php
            $dir = $path . '/images';
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