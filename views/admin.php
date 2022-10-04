<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');

admin_block();

?>

<?php start_content_full(1, 'login'); ?>

<a href="/eventpanel">Event panel</a>
<br>
<a href="/graphics">Graphics</a>
<br>
<a href="/reg/hs">HS Registrations</a>
<br>
<a href="/reg/ymca">YMCA Registrations</a>

<?php end_content_full(1); ?>