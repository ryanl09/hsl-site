<?php

require_once($path . '/classes/services/ReportErrorService.php');

$action = $_POST['action'];

switch ($action){
    case 'report_error':
        if (!isset($_POST['e_page']) || !isset($_POST['e_msg']) || !isset($_POST['e_fn'])){
            echo ajaxerror::e('errors', ['Missing fields']);
            die();
        }

        $params = array(
            'page' => $_POST['e_page'],
            'error_msg' => $_POST['e_msg'],
            'fn' => $_POST['e_fn']
        );

        $res = new ReportErrorService($db);
        $s = $res->insert($params);

        echo json_encode(
            array(
                'status' => 1,
                'success' => 'The error has been reported. We will look into it ASAP!'
            )
        );

        break;
}

?>