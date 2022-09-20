<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');

if (!isset($_SESSION['user']) || (isset($_SESSION['user']) && $_SESSION['user']->get_role() !== 'admin')) {
    header('Location: ' . href('dashboard'));
}
?>


<?php start_content_full(1, 'login'); ?>

<a href="/eventpanel">Event panel</a>
<br>
<a href="/graphics">Graphics</a>

<?php phpinfo();?>

<?php end_content_full(1); ?>