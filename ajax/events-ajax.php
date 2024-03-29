<?php

require_once('ajax-util.php');
require_once($path . '/classes/event/Event.php');
require_once($path . '/classes/general/Game.php');
require_once($path . '/classes/general/Stats.php');

$action = $_GET['action'];

switch ($action) {
    case 'get_today':
        if (!isset($_GET['game'])){
            echo ajaxerror::e('errors', ['Game not set']);
        }

        $game = $_GET['game'];
        $events = Event::all_today_game($db, $game);
        echo json_encode(
            array(
                'status' => 1,
                'events' => $events
            )
        );
        die();
        break;
    case 'get_current':
        $e = Event::is_now($db);
        echo json_encode(
            array(
                'status' => 1,
                'now' => $e,
                'next' => Event::get_next($db)
            )
        );
        die();
        break;
    case 'all_events':
        if (!isset($_GET['sort-team']) || !isset($_GET['sort-div']) || !isset($_GET['game'])){
            echo ajaxerror::e('errors', ['Missing fields']);
            die();
        }

        $game=$_GET['game'];
        $team = $_GET['sort-team'];
        $div = $_GET['sort-div'];
        $time = $_GET['time'];

        if (!is_numeric($team) || !is_numeric($div) || !is_numeric($game)){
            echo ajaxerror::e('errors', ['Invalid team or division']);
            die();
        }

        $times=['all','upcoming','past'];
        if (!in_array($time, $times)){
            echo ajaxerror::e('errors', ['Invalid time']);
            die();
        }

        $e = [];
        $e = Event::sort_by($db, $game, $team, $div, $time);

        echo json_encode(
            array(
                'status' => 1,
                'events' => $e
            )
        );
        die();
        break;
    case 'all_events_calendar':
        if (!isset($_GET['sort-team-calendar']) || !isset($_GET['sort-div-calendar'])){
            echo ajaxerror::e('errors', ['Missing fields']);
            die();
        }

        $team = $_GET['sort-team-calendar'];
        $div = $_GET['sort-div-calendar'];

        if (!is_numeric($team) || !is_numeric($div)){
            echo ajaxerror::e('errors', ['Invalid team or division']);
            die();
        }

        $e = [];
        $e = Event::sort_by_calendar($db, $team, $div);

        echo json_encode(
            array(
                'status' => 1,
                'events' => $e
            )
        );
        die();
        break;
    case 'get_teams':
        if (!isset($_GET['game']) || !isset($_GET['div'])){
            echo ajaxerror::e('errors', ['No game or division set']);
            die();
        }
        
        $game = $_GET['game'];
        $div = $_GET['div'];

        $t = Game::get_teams($db, $game, $div);
        echo json_encode(
            array(
                'status' => 1,
                'teams' => $t
            )
        );
        die();
        break;
    case 'get_all_stats':
        if (!isset($_GET['game']) || !isset($_GET['div']) || !isset($_GET['team'])){
            echo ajaxerror::e('errors', ['No game or division set']);
            die();
        }
        
        $g = $_GET['game'];
        $d = $_GET['div'];
        $t = $_GET['team'];

        $stats = new Stats($db);
        $cols = $stats->get_cols_game($g);
        $s = $stats->stats_page_any($g, $d);

        echo json_encode(
            array(
                'status' => 1,
                'cols' => $cols,
                'stats' => $s
            )
        );
        die();

        break;

    case 'get_top_players':
        // check for inputs
        if (!isset($_GET['game']) || !isset($_GET['div']) || !isset($_GET['stat_id'])){
            echo ajaxerror::e('errors', ['No game or division or stat_id set']);
            die();
        }
        
        $game = $_GET['game'];
        $div = $_GET['div'];
        $stat_id = $_GET['stat_id'];

        $stats = new Stats($db);
        $s = $stats->get_top_players_of_week($game, $div, $stat_id);

        echo json_encode(
            array(
                'status' => 1,
                'stats' => $s
            )
        );

        break;
    default:
        echo ajaxerror::e('errors', ['Invalid action']);
        break;
}

?>