<?php

require_once('CreatePlayerService.php');
require_once('CreateTeamManagerService.php');

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/classes/services/VerifyService.php');

class RegisterService extends VerifyService {
    private $type;

    public function __construct($params, $type) {
        parent::_construct($params);
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

        $email = $this->check_input('email');
        if (isset($email['error'])) {
            $errors[] = 'Email is empty';
        }

        $username = $this->check_input('username');
        if (isset($username['error'])) {
            $errors[] = 'Username is empty';
        }

        $password = $this>check_input('password');
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
        $email = $email['val'];
        $username = $username['val'];
        $password = $password['val'];
        $c_password = $c_password['val'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email';
        }

        $u_len = strlen($username);
        if ($u_len < MIN_USER_LENGTH || $u_len > MAX_USER_LENGTH) {
            $errors[] = 'Invalid username length';
        }

        $p_len = strlen($password);
        if ($p_len < MIN_PASS_LENGTH || $p_len > MAX_PASS_LENGTH) {
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

        $errors = [];
    }
}

?>