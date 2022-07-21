<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/classes/security/PasswordHash.php');
require_once($path . '/classes/services/VerifyService.php');

class LoginService extends VerifyService {

    public function __construct($params) {
        parent::__construct($params);
    }

    /**
     * Log the user in
     * @return  array
     */

    public function login() {
        if (!$this->params) {
            return array(
                'errors' => 'Missing parameters'
            );
        }

        $errors = [];

        $username = $this->check_input('username');
        if (isset($username['error'])) {
            $errors[] = 'Username is empty';
        }

        $password = $this>check_input('password');
        if (isset($password['error'])) {
            $errors[] = 'Password is empty';
        }

        if (count($errors) > 0) {
            return array(
                'status' => 0,
                'errors' => $errors
            );
        }

        $ph = new PasswordHash();
        $hash = $ph->create($password);

        $query = 
        "SELECT `user_id`, `password`
        FROM `users`
        WHERE `email` = ? OR `username` = ?";

        $result = $this->db($query, $username, $username);

        if (!$result->numRows()) {
            return array(
                'status' => 0,
                'errors' => ['No account exists with that username or email'];
            );
        }

        $arr = $result->fetchArray();
        $pass = $arr['password'];

        $v = PasswordHash::verify($password, $pass);
        if (!$v) {
            return array(
                'status' => 0,
                'errors' => ['Password is wrong']
            );
        }

        return array(
            'status' => 1,
            'user_id' => $arr['user_id']
        );
    }
    
}

?>