<?php

require_once($path . '/classes/event/Tournament.php');

if ($method==='GET'){
    
    if (!isset($_GET['t_id'])){
        echo ajaxerror::e('errors', ['Missing tournament id']);
        die();
    }
    
    $t_id = $_GET['t_id'];
    $action = $_GET['action'];

    $t = new Tournament($db, $t_id);

    switch ($action){
        case 'get_tournament':
            $rounds = $t->get_rounds();
            break;
    }

}else if ($method==='POST'){
    $action = $_POST['action'];

    switch ($action){
        case 'create_playoffs':
            if (!isset($_POST['game']) || !isset($_POST['title'])){
                echo ajaxerror::e('errors', ['Missing fields']);
                die();
            }

            $game = $_POST['game'];
            $title = $_POST['title'];

            $t_id = Tournament::create($db, $game, $title);

            if ($t_id){
                echo json_encode(
                    array(
                        'status' => 1,
                        'id' => $t_id,
                        'success' => 'Tournament created!'
                    )
                );
                die()
            }

            echo ajaxerror::e('errors', ['Could not create tournament']);
            die();
            break;
    }

}else{
    echo ajaxerror::e('errors', ['Invalid request']);
    die();
}

?>