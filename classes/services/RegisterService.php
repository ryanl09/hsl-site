<?php

require_once('CreatePlayerService.php');
require_once('CreateTeamManagerService.php');

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/classes/services/VerifyService.php');
require_once($path . '/classes/security/AuthToken.php');
require_once($path . '/classes/security/PasswordHash.php');

class RegisterService extends VerifyService {
    const MIN_USER_LENGTH = 3;
    const MAX_USER_LENGTH = 20;
    const MIN_PASS_LENGTH = 8;
    const MAX_PASS_LENGTH = 100;


    private $type;

    public function __construct($params, $type) {
        parent::__construct($params);
        $this->type = $type;
    }

    /**
     * Registers a user based on what type they are
     * @param   array   $params
     * @return  int|Player|TeamManager|Caster|Admin|College
     */

    public function create_user($params) {
        $success = false;
        $cs = 0;
        switch ($type) {
            case 'player':
                $cs = new CreatePlayerService();
                break;
            case 'team_manager':
                $cs = new CreateTeamManagerService();
                break;
            case 'caster':
                $cs = new CreateCasterService();
                break;
            case 'admin':
                $cs = new CreateAdminService();
                break;
            case 'staff':
                //?
                break;
            case 'college':
                $cs = new CreateCollegeService();
                break;
        }

        if (!$cs){
            return 0;
        }

        $user = $cs->create();
        return $user;
    }

    public function register() {
        
        if (!$this->params) {
            return array(
                'errors' => 'Missing parameters'
            );
        }

        $errors = [];

        $f_name = $this->check_input('f_name');
        if (isset($f_name['error'])) {
            $errors[] = 'First name is empty';
        }

        $l_name = $this->check_input('l_name');
        if (isset($l_name['error'])) {
            $errors[] = 'Last name is empty';
        }

        $email = $this->check_input('email');
        if (isset($email['error'])) {
            $errors[] = 'Email is empty';
        }

        $pronouns = $this->check_input('pronouns');
        if (isset($pronouns['error'])) {
            $errors[] = 'Pronouns are empty';
        }

        $username = $this->check_input('username');
        if (isset($username['error'])) {
            $errors[] = 'Username is empty';
        }

        $password = $this->check_input('password');
        if (isset($password['error'])) {
            $errors[] = 'Password is empty';
        }

        $c_password = $this->check_input('c_password');
        if (isset($c_password['error'])) {
            $errors[] = 'Confirm password is empty';
        }

        if (count($errors) > 0) {
            return array(
                'status' => 0,
                'errors' => $errors
            );
        }

        $errors = [];
        $f_name = $f_name['val'];
        $l_name = $l_name['val'];
        $pronouns = $pronouns['val'];
        $email = $email['val'];
        $username = $username['val'];
        $password = $password['val'];
        $c_password = $c_password['val'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email';
        }

        $u_len = strlen($username);
        if ($u_len < self::MIN_USER_LENGTH || $u_len > self::MAX_USER_LENGTH) {
            $errors[] = 'Invalid username length';
        }

        $p_len = strlen($password);
        if ($p_len < self::MIN_PASS_LENGTH || $p_len > self::MAX_PASS_LENGTH) {
            $errors[] = 'Invalid password length';
        }

        if ($password !== $c_password) {
            $errors[] = 'Passwords do not match';
        }

        if (count($errors) > 0) {
            return array(
                'status' => 0,
                'errors' => $errors
            );
        }

        if (!$this->validate_name($f_name) || !$this->validate_name($l_name)) {
            return array(
                'status' => 0,
                'errors' => ['Name can only include letters']
            );
        }

        if (!$this->validate_username($username)) {
            return array(
                'status' => 0,
                'errors' => ['Username must be between 3 and 20 characters, and can contain only: letters (a-Z), numbers (0-9), and underscores (_)']
            );
        }
        
        if (!$this->validate_password($password)) {
            return array(
                'status' => 0,
                'errors' => ['Password must be at least 8 characters, and contain at least: 1 lowercase letter, 1 uppercase letter, 1 number, and 1 special character']
            );
        }

        $name = ucwords(strtolower($f_name)) . ' ' . ucwords(strtolower($l_name));

        $auth = new AuthToken();
        $activation_key = $auth->create();

        $ph = new PasswordHash($password);
        $password = $ph->create();

        $query =
        "INSERT INTO `users` (`name`, `email`, `pronouns`, `username`, `password`, `activation_key`, `activated`)
        VALUES (?, ?, ?, ?, ?, ?, false)";

        $user_id = $this->db->query($query, $name, $email, $pronouns, $username, $password, $activation_key)->lastInsertID();

        return array(
            'status' => 1,
            'user_id' => $user_id
        );
    }

    private function validate_name($name) {
        return preg_match("/^[a-zA-z]*$/", $name);
    }

    private function validate_username($username) {
        return preg_match("/[a-zA-Z0-9_]{" . self::MIN_USER_LENGTH . "," . self::MAX_USER_LENGTH . "}+$/", $username);
    }

    private function validate_password($password) {
        return preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{". self::MIN_PASS_LENGTH ."," . self::MAX_PASS_LENGTH . "}$/", $password);
    }
}

?>