<?php

$path = $_SERVER['DOCUMENT_ROOT'];

require_once($path . '/documentelements.php');
require_once($path . '/classes/user/User.php');

$user_id = wp_get_current_user()->ID;

$u = new User($user_id);

?>

<html>
    <?php base_header(); ?>
    <body>
        <?php print_navbar($u->get_username()); ?>
        <section class="home">
            <h2 class="page-title">Dashboard</h2>
            <div class="text">
                <?php include($path . '/controllers/ProfileController.php'); ?>
            </div>
        </section>

        <?php ui_script() ?>

    </body>
</html>