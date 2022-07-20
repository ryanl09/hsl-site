<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/classes/user/User.php');

if (User::exists()) {

}

?>

<!DOCTYPE html>
<html lang="en">
<?php base_header(); ?>
    <body>
        <?php print_navbar($u->get_username());?>
        <section class="home">
            <h2 class="page-title">Dashboard</h2>
            <div class="text">
            </div>
        </section>

        <?php ui_script() ?>

    </body>
</html>