<?php

require_once($path . '/classes/services/CSVService.php');
require_once($path . '/classes/user/Player.php');

$action = $_GET['action'];

switch($action){
    case 'get_players':
        $players = Player::get_all($db);
        $csv = new CSVService();
        $str = $csv->format($players);

        echo json_encode(
            array(
                'status' => 1,
                'data' => $str
            )
        );
        break;
}

?>