<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');
require_once($path . '/classes/util/tecdb.php');

start_content_full(1, 'event');

// latest matchup
//weekly matchup
//breakdown by games
//sort search

$db = new tecdb();

$query=
"SELECT events.event_date, events.event_time, teams.team_name, events.event_stream
FROM `events`
INNER JOIN subteams
    ON subteams.id = events.event_home OR subteams.id = events.event_away
INNER JOIN `teams`
    ON subteams.team_id = teams.id
GROUP BY events.event_date, events.event_time, teams.team_name, events.event_stream";

$csv = "Date,Time,Home,Away,Stream\n";

$row = $db->query($query)->fetchAll();

for ($j = 0; $j < count($row); $j += 2){
    $csv .= $row[$j]['event_date'] . ',' . $row[$j]['event_time'] . ',' . $row[$j]['team_name'] . ','
        . $row[($j+1)]['team_name'] . ',' . $row[$j]['event_stream'] ."\n";
}

echo '<script>';
echo 'console.log("csv:");';
echo 'console.log("'.$csv.'");';
echo '</script>';

?>



<?php end_content_full(); ?>