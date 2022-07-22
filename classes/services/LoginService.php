<?php

$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path . '/documentelements.php');
require_once($path . '/classes/security/PasswordHash.php');
require_once($path . '/classes/services/VerifyService.php');
require_once($path . '/classes/user/User.php');

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

        $password = $this->check_input('password');
        if (isset($password['error'])) {
            $errors[] = 'Password is empty';
        }

        if (count($errors) > 0) {
            return array(
                'status' => 0,
                'errors' => $errors
            );
        }

        $username = $username['val'];
        $password = $password['val'];
        $ph = new PasswordHash($password);
        $hash = $ph->create();

        $query = 
        "SELECT `user_id`, `password`
        FROM `users`
        WHERE `email` = ? OR `username` = ?";

        $result = $this->db->query($query, $username, $username);

        if (!$result->numRows()) {
            return array(
                'status' => 0,
                'errors' => ['No account exists with that username or email']
            );
        }

        $arr = $result->fetchArray();
        $pass = $arr['password'];

        $v = $ph->verify($pass);
        if (!$v) {
            return array(
                'status' => 0,
                'errors' => ['Password is wrong']
            );
        }

        $user_id = $arr['user_id'];
        return $this->login_user($user_id);
    }

    public function login_user($user_id) {
        $user = new User($user_id);

        if (session_id()) {
            session_regenerate_id();
        }

        $_SESSION['user'] = $user;

        return array(
            'status' => 1,
            'user_id' => $user_id,
            'href' => href('dashboard')
        );
    }
}

?>