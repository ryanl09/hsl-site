<?php

require_once($path . '/classes/general/Game.php');

$action = $_GET['action'];

switch ($action) {
    case 'get_all':
        echo json_encode(
            array(
                'status' => 1,
                'games' => Game::get_all($db)
            )
        );

        break;
}

?>