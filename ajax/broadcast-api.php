<?php

header("Access-Control-Allow-Headers: Cache-Control, Pragma, Origin, Authorization, Content-Type, X-Requested-With, Auth-User, Auth-Token");
header("Access-Control-Allow-Origin: *");

$path = $_SERVER["DOCUMENT_ROOT"];

require_once($path . '/ajax/ajax-util.php');
require_once($path . '/classes/general/Season.php');
require_once($path . '/classes/team/SubTeam.php');
include_once($path . '/classes/util/ajaxerror.php');
include_once($path . '/classes/util/Sessions.php');
require_once($path . '/classes/util/tecdb.php');
require_once($path . '/classes/security/ValidateAuth.php');

header("Access-Control-Allow-Methods: GET, OPTIONS, POST");
header('Content-Type: application/json; charset=utf-8');

//if (!isset($_SESSION['user']) || $_SESSION['user']->get_username() != 'ryan')
//{
    $get = check_get();

    if (!$get){
        echo ajaxerror::e('errors', ['Invalid request type.']);
        die();
    }

    $headers = getallheaders();

    if (!isset($headers['Auth-User']) || !isset($headers['Auth-Token'])){
        echo ajaxerror::e('errors', ['Authentication headers not set.']);
        die();
    }

    $user = $headers['Auth-User'];
    $token = $headers['Auth-Token'];

    $valid = ValidateAuth::broadcast($user, $token);

    if (!$valid){
        echo ajaxerror::e('errors', ['Invalid credentials.']);
        die();
    }

    if (!isset($_GET['action'])){
        echo ajaxerror::e('errors', ['Action not set.']);
    }
//}

$action = $_GET["action"];
switch ($action){
    case 'get_teams':
        $arr = array(
            'presets' => []
        );

        $db = new tecdb();

        $c = Season::get_current($db);

        $rocket_league = 1;
        $div = 1;
        if (isset($_GET['division']) && is_numeric($_GET['division'])) {
            $div = $_GET['division'];
        }

        $sql = 
        "SELECT teams.team_name AS parent, teams.primarycolor, teams.secondarycolor, teams.team_logo, teams.id, subteams.id AS sub_id, subteams.division, games.game_name, subteams.tag
        FROM teams
        INNER JOIN subteams
            ON subteams.team_id = teams.id
        INNER JOIN games
            ON games.id = subteams.game_id AND games.id = ?
        WHERE teams.team_name NOT LIKE \"%ryan%\" AND teams.team_name NOT LIKE \"%TEC%\" AND teams.team_name NOT LIKE \"%Test%\"
        AND subteams.id IN (
            SELECT `subteam_id`
            FROM `subteam_seasons`
            WHERE `season_id` = ?
        )
        AND subteams.division = ?
        ORDER BY teams.team_name, games.game_name, subteams.division";

        $res = $db->query($sql, $rocket_league, $c, $div)->fetchAll();

        for ($i = 0; $i < count($res); $i++){
            $rec = SubTeam::get_record($db, $res[$i]['sub_id']);

            array_push($arr['presets'], [
                $res[$i]['parent'] . " - " . $res[$i]['game_name'] . ' D' . $res[$i]['division'],
                array(
                    "TeamName" => $res[$i]['parent'],
                    "TeamSubName" => '(' . $rec['wins'] . ' - ' . $rec['losses'] . ')',
                    "TeamColor" => $res[$i]['primarycolor'],
                    "TeamLogo" => $res[$i]['team_logo']
                )
            ]);
        }

        echo json_encode($arr);

        break;
    default:
        echo ajaxerror::e('errors', ['Unrecognized action.']);
        break;
}

/*

team logos
team color
team shortname
team subname => record

*/


?>