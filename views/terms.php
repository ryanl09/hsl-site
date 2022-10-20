<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');
require_once($path . '/classes/general/Stats.php');

start_content_full(1, 'login');

echo '<pre>';
$stats = new Stats();
print_r($stats->get_top_players_of_week(1, 1, 1));
echo '</pre>';

?>

<?php end_content_full(1); ?>