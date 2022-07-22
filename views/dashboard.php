<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');

$u = new User($user_id);

?>

<html>
    <?php base_header(); ?>
    <body>
        <?php print_navbar($u);?>
        <section class="home">
            <h2 class="page-title">Dashboard</h2>
            <div class="page-content">
            </div>
        </section>

        <?php ui_script() ?>

    </body>
</html>