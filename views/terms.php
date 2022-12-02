<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');
require_once($path . '/classes/general/Stats.php');

start_content_full(1, 'login');


$team = "forest";
$team2 = "bish";
$game = 2;

$query=
"SELECT t1.team_name, s1.id, s1.game_id, s1.division
FROM subteams s1
INNER JOIN teams t1
    ON LOWER(t1.team_name) LIKE '%$team%'
WHERE s1.team_id = t1.id AND s1.game_id = $game";

$query2=
"SELECT t1.team_name, s1.id, s1.game_id, s1.division
FROM subteams s1
INNER JOIN teams t1
    ON LOWER(t1.team_name) LIKE '%$team2%'
WHERE s1.team_id = t1.id AND s1.game_id = $game";

$res = $db->query($query)->fetchAll();

$res2 = $db->query($query2)->fetchAll();

echo '<pre>';
print_r($res);
print_r($res2);
echo '</pre>';

?>

<?php end_content_full(1); ?>