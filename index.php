<?php

$path = $_SERVER['DOCUMENT_ROOT'];
/*
require_once($path . '/documentelements.php');

require_once($path . '/classes/event/Event.php');

require_once($path . '/classes/services/CreateEventService.php');
require_once($path . '/classes/services/CreateTeamService.php');

require_once($path . '/classes/team/SubTeam.php');
require_once($path . '/classes/team/Team.php');

require_once($path . '/classes/user/User.php');
require_once($path . '/classes/user/Player.php');*/

require_once($path . '/classes/util/ClientRequest.php');



//require_once('classes/util/TECDB.php');

$args = $_SERVER["REQUEST_URI"];
$arg_arr = explode("/",$args);
$page = strtolower($arg_arr[1]);

$req = new ClientRequest($page);

?>