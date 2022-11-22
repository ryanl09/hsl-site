<?php

require_once('ReportService.php');

class ReportErrorService extends ReportService {
    public function __construct($db) {
        parent::__construct($db);
    }

    /**
     * report error on ajax calls
     * @param   array   $params
     * @return  boolean
     */

    public function insert($params) {
        if (!isset($params['page']) || !isset($params['error_msg']) || !isset($params['fn'])) {
            return false;
        }

        $user_id = 0;
        if (isset($_SESSION['user'])){
            $user_id = $_SESSION['user']->get_id();
        }

        $page = $params['page'];
        $error_msg = $params['error_msg'];
        $fn = $params['fn'];

        $query=
        "INSERT INTO `reported_errors` (`user_id`, `page`, `error_msg`, `fn`)
        VALUES (?, ?, ?, ?)";

        $res = $this->db->query($query, $user_id, $page, $error_msg, $fn)->affectedRows();
        return $res > 0;
    }
}

?>