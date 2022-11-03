<?php
$path = $_SERVER["DOCUMENT_ROOT"];

require_once($path . '/classes/general/Announcements.php');
require_once($path . '/classes/general/Season.php');
require_once($path . '/classes/team/SubTeam.php');
require_once($path . '/classes/user/TempUser.php');
require_once($path . '/classes/user/User.php');

if (!isset($_SESSION['user']))
{
    echo ajaxerror::e('errors', ['Invalid permissions']);
    die();
}

$action = $_POST["action"];
switch ($action){
    case 'add_announcement':
        if (!$_SESSION['user']->is_admin()) {
            echo ajaxerror::e('errors', ['Invalid permissions']);
            die();
        }

        if (!isset($_POST['a-title']) || !isset($_POST['a-body'])){
            echo ajaxerror::e('errors', ['Missing fields']);
            die();
        }

        $title = $_POST['a-title'];
        $body = $_POST['a-body'];

        $created = Announcements::create($db, $title, $body);

        echo json_encode(
            array(
                'status' => 1,
                'created' => $created
            )
        );

        break;

    case 'delete_announcement':
        if (!$_SESSION['user']->is_admin()) {
            echo ajaxerror::e('errors', ['Invalid permissions']);
            die();
        }

        if (!isset($_SESSION['user']) || !isset($_POST['announcement_id'])){
            echo ajaxerror::e('errors', ['Missing fields']);
            die();
        }

        if (!$_SESSION['user']->is_admin()){
            echo ajaxerror::e('errors', ['Invalid permissions']);
            die();
        }

        $announcement_id = $_POST['announcement_id'];

        $created = Announcements::delete($db, $announcement_id);
        echo json_encode(
            array(
                'status' => 1,
                'success'=>'Announcement removed'
            )
        );
        die();

        break;

    case 'add_temp_pl':
        if (!$_SESSION['user']->is_admin()) {
            echo ajaxerror::e('errors', ['Invalid permissions']);
            die();
        }

        if (!isset($_POST['ign']) || !isset($_POST['team'])){
            echo ajaxerror::e('errors', ['Missing fields']);
            die();
        }

        $ign = $_POST['ign'];
        $team_id = $_POST['team'];
        $user_id = TempUser::create($db, $ign, $team_id);

        echo json_encode(
            array(
                'status' => 1,
                'id' => $user_id
            )
        );
        die();
        break;
    case 'allocate_temp_pl':
        if (!$_SESSION['user']->is_admin()) {
            echo ajaxerror::e('errors', ['Invalid permissions']);
            die();
        }

        if (!isset($_POST['id']) || !isset($_POST['game']) || !isset($_POST['div'])){
            echo ajaxerror::e('errors', ['Missing fields']);
            die();
        }

        $pl_id = $_POST['id'];
        $game = $_POST['game'];
        $div = $_POST['div'];

        $exists = SubTeam::exists($db, $pl_id, $game, $div);

        if (!$exists){
            echo ajaxerror::e('errors', ['No team found with that user, game, and division']);
            die();
        }

        $team_id = $res['id'];
        $s = new SubTeam($db, $team_id);
        $done = $s->add_player($pl_id);

        $u = new User($db, $pl_id);

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
        break;
    case 'get_announcements':
        $a = Announcements::get_all($db);
        echo json_encode(
            array(
                'status' => 1,
                'announcements' => $a
            )
        );
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