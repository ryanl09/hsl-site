<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');

if (!isset($_SESSION['user']) || (isset($_SESSION['user']) && $_SESSION['user']->get_role() !== 'admin')) {
    header('Location: ' . href('dashboard'));
}
?>

<html>
    <?php 
    base_header(array(
        'styles' => ['login'],
        'scripts' => ['login']
        )
    ); 
    ?>
    <body>
        <?php print_navbar();?>
        <section class="home">
            <div class="page-content">
                <input type="hidden" id="csrf" value="<?php echo $_SESSION['csrf']; ?>">
                <a href="/eventpanel">Event panel</a>
            </div>
        </section>
    </body>
</html>