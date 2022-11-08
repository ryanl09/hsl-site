<?php

require_once($path . '/classes/general/Standings.php');

$action = $_GET['action'];

switch ($action){
    case 'get_standings':
        if (!isset($_GET['game_id']) || !isset($_GET['div'])){
            echo ajaxerror::e('errors', ['Missing fields']);
            die();
        }

        $game_id = $_GET['game_id'];
        $div = $_GET['div'];
        $stnd = Standings::get($db, $game_id, $div);

        echo json_encode(
            array(
                'status' => 1,
                'standings' => $stnd
            )
        );
        break;
}

?>