<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');
require_once($path . '/classes/user/User.php');

require_once($path . '/classes/util/Sessions.php');

$args = $_SERVER["REQUEST_URI"];
$arg_arr = explode("/",$args);

$view = new User(strtolower($arg_arr[2]));

?>

<!DOCTYPE html>
<html lang="en">
<?php 
base_header(
    array(
        'styles' => ['profile']
    )
); 
?>
    <body>
        <?php print_navbar();?>
        <section class="home">
            <?php if ($view->get_id()) { //if user exists ?>
                <div class="banner-wrapper">
                    <div class="banner">
                        <div class="pfp">
                            <img src="<?php echo $view->profile_image(); ?>">
                        </div>
                    </div>
                </div>
            <? } else { //user doesn't exist ?>

            <?php } ?>
            <div class="page-content"></div>
        </section>

        <?php ui_script(); ?>

    </body>
</html>