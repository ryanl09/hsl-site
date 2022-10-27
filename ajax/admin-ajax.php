<?php
$path = $_SERVER["DOCUMENT_ROOT"];

require_once($path . '/ajax/ajax-util.php');
require_once($path . '/classes/general/Season.php');
include_once($path . '/classes/security/csrf.php');
require_once($path . '/classes/team/SubTeam.php');
include_once($path . '/classes/util/ajaxerror.php');
include_once($path . '/classes/util/Sessions.php');
require_once($path . '/classes/util/tecdb.php');
require_once($path . '/classes/user/TempUser.php');
require_once($path . '/classes/user/User.php');

if (!isset($_SESSION['user']))
{
    echo ajaxerror::e('errors', ['Invalid permissions']);
    die();
}

if (!$_SESSION['user']->is_admin()) {
    echo ajaxerror::e('errors', ['Invalid permissions']);
    die();
}

$post = check_post();
if (!$post['status']) {
    echo ajaxerror::e('errors', [$get['error']]);
    die();
}

$csrf = CSRF::post();
if (!$csrf) {
    echo ajaxerror::e('errors', ['Invalid CSRF token']);
    die();
}

$action = $_POST["action"];
switch ($action){
    case 'add_announcement':
        if (!isset($_POST['a-title']) || !isset($_POST['a-body'])){
            echo ajaxerror::e('errors', ['Missing fields']);
            die();
        }

        $title = $_POST['a-title'];
        $body = $_POST['a-body'];
        require_once($path . '/classes/general/Announcements.php');

        $created = Announcements::create($title, $body);

        echo json_encode(
            array(
                'status' => 1,
                'created' => $created
            )
        );

        break;

    case 'delete_announcement':
        if (!isset($_SESSION['user']) || !isset($_POST['announcement_id'])){
            echo ajaxerror::e('errors', ['Missing fields']);
            die();
        }

        if (!$_SESSION['user']->is_admin()){
            echo ajaxerror::e('errors', ['Invalid permissions']);
            die();
        }

        $announcement_id = $_POST['announcement_id'];
        require_once($path . '/classes/general/Announcements.php');

        $created = Announcements::delete($announcement_id);
        //$db = new tecdb();

        //$query = 
        //"DELETE FROM `announcements`
        //WHERE `announcement_id` = ?";

        //$res = $db->query($query, $announcement_id)->affectedRows();
        //if ($res > 0){
            echo json_encode(
                array(
                    'status' => 1,
                    'success'=>'Announcement removed'
                )
            );
            die();
        //}

        echo ajaxerror::e('errors', ['Couldn\'t remove player from roster']);
        die();

        break;

    case 'add_temp_pl':
        if (!isset($_POST['ign']) || !isset($_POST['team'])){
            echo ajaxerror::e('errors', ['Missing fields']);
            die();
        }

        $ign = $_POST['ign'];
        $team_id = $_POST['team'];
        $user_id = TempUser::create($ign, $team_id);

        echo json_encode(
            array(
                'status' => 1,
                'id' => $user_id
            )
        );
        die();
        break;
    case 'allocate_temp_pl':
        if (!isset($_POST['id']) || !isset($_POST['game']) || !isset($_POST['div'])){
            echo ajaxerror::e('errors', ['Missing fields']);
            die();
        }

        $pl_id = $_POST['id'];
        $game = $_POST['game'];
        $div = $_POST['div'];

        $db = new tecdb();

        $query=
        "SELECT subteams.id
        FROM subteams
        INNER JOIN users
            ON users.user_id = ?
        WHERE subteams.team_id = users.team_id AND subteams.division = ? AND subteams.game_id = ?";

        $res = $db->query($query, $pl_id, $div, $game)->fetchArray();

        if (empty($res)){
            echo ajaxerror::e('errors', ['No team found with that user, game, and division']);
            die();
        }

        $team_id = $res['id'];
        $s = new SubTeam($team_id);
        $done = $s->add_player($pl_id);

        $u = new User($pl_id);

        $added_ign = false;

        if ($done){
            $query=
            "INSERT INTO `user_igns`
            VALUES (?, ?, ?)";

            $res = $db->query($query, $pl_id, $game, $u->get_username())->affectedRows();

            $added_ign = $res > 0;
        }

        echo json_encode(
            array(
                'status' => 1,
                'added' => $done,
                'able_to_set_ign' => $added_ign
            )
        );
        die();

        break;
    default:
        echo ajaxerror::e('errors', ['Unrecognized action.']);
        die();
        break;
}

/*

team logos
team color
team shortname
team subname => record

*/


?>