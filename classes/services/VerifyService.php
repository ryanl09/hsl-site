<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/classes/util/tecdb.php');

abstract class VerifyService {
    protected $db;
    protected $params;

    public function __construct($params) {
        $this->params = $params;
        $this->db = new tecdb();
    }

    final protected function check_input($key) {
        if (isset($this->params[$key])) {
            if (empty($this->params[$key])) {
                return array(
                    'error' => 1
                );
            } else {
                $val = $this->params[$key];
                if ($key !== 'password' && $key !== 'c_password') {
                    $val = stripslashes($val);
                    $val = trim($val);
                    $val = htmlspecialchars($val);
                }
                return array(
                    'val' => $val
                );
            }
        } else {
            return array(
                'error' => 1
            );
        }
    }
}

?>