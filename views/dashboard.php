<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');
require_once($path . '/classes/util/Sessions.php');
require_once($path . '/classes/team/Team.php');

?>

<html>
    <?php base_header(); ?>
    <body>
        <?php print_navbar();?>
        <section class="home">
            <h2 class="page-title">Dashboard</h2>
            <div class="page-content">

            </div>
        </section>

        <?php ui_script() ?>

    </body>
</html>