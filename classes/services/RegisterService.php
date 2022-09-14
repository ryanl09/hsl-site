<?php

require_once('CreatePlayerService.php');
require_once('CreateTeamService.php');
require_once('LoginService.php');

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/classes/team/Team.php');
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
     * do user meta & other actions based on type
     * @param   array   $params
     * @return  int|Player|TeamManager|Caster|Admin|College
     */

    private function create_user() {
        $success = false;
        $cs = 0;
        switch ($this->type) {
            case 'player':
            case 'team_manager':
            case 'caster':
            case 'admin':
            case 'staff':
            case 'college':
                $cs=1;
                break;
            default:
                return array(
                    'status' => 0,
                    'errors' => ['Invalid user type']
                );
        }

        $data = 1;//$cs->create();
        return $data;
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

        $school = '';
        $mascot = '';
        $phone = '';
        $pcolor = '';
        $scolor = '';

        if ($this->type==='team_manager' || $this->type==='college') {
            $school = $this->check_input('school');
            if (isset($school['error'])) {
                $errors[] = 'School name is empty';
            } else {
                $school = $school['val'];
            }

            $mascot = $_POST['mascot'] ?? '';

            $phone = $this->check_input('phone');
            if (isset($phone['error'])) {
                $errors[] = 'Phone number is empty';
            } else {
                $phone = $phone['val'];
            }

            $pcolor = $this->check_input('primarycolor');
            if (isset($pcolor['error'])) {
                $errors[] = 'Primary color is empty';
            } else {
                $pcolor = $pcolor['val'];
            }

            $scolor = $this->check_input('secondarycolor');
            if (isset($scolor['error'])) {
                $errors[] = 'Secondary color is empty';
            } else {
                $scolor = $scolor['val'];
            }
        }

        if (count($errors) > 0) {
            return array(
                'status' => 0,
                'errors' => $errors
            );
        }

        $errors = [];
        $f_name = strtolower($f_name['val']);
        $l_name = strtolower($l_name['val']);
        $pronouns = strtolower($pronouns['val']);
        $email = strtolower($email['val']);
        $username = strtolower($username['val']);
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

        $user_exists = $this->user_exists($username);
        if ($user_exists) {
            return array(
                'status' => 0,
                'errors' => ['An account with this username already exists']
            );
        }

        $email_exists = $this->email_exists($email);
        if ($email_exists) {
            return array(
                'status' => 0,
                'errors' => ['An account with this email already exists']
            );
        }

        $team_id = 0;
        if($this->type === 'player'){
            $code = $_POST['schoolcode'];
            $team_id = Team::from_schoolcode($code);
        }

        $discord = $_POST['discord'] ?? '';

        $name = ucwords($f_name) . ' ' . ucwords($l_name);

        $auth = new AuthToken();
        $activation_key = $auth->create();
        $auth = new AuthToken(6);
        $request_key = $auth->create();

        $ph = new PasswordHash($password);
        $password = $ph->create();


        $query =
        "INSERT INTO `users` (`name`, `email`, `pronouns`, `username`, `password`, `activation_key`, `activated`, `role`, `team_id`, `discord`, `request_key`)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $user_id = $this->db->query($query, $name, $email, $pronouns, $username, $password, $activation_key, 0, $this->type, $team_id, $discord, $request_key)->lastInsertID();

        $create_type = $this->create_user();
        if (isset($create_type['errors'])) {
            return $create_type;
        }
        switch ($this->type) {
            case 'team_manager':
            case 'college':
                $cts = new CreateTeamService();
                $team_id = $cts->create(
                    array(
                        'name' => $school,
                        'user_id' => $user_id,
                        'logo' => '',
                        'mascot' => $mascot,
                        'primarycolor' => $pcolor,
                        'secondarycolor' => $scolor
                    )
                );

                $query = "UPDATE users SET team_id = ? WHERE `user_id` = ?";
                $this->db->query($query, $team_id, $user_id)->affectedRows();
                break;
        }

        $login_params = array(
            'ref' => 'register'
        );
        if ($this->type === 'team_mananger' || $this->type === 'college') {
            //create team service
           
        }

        $ls = new LoginService($login_params);
        $ls->login_user($user_id);

        return array(
            'status' => 1,
            'user_id' => $user_id,
            'href' => '/dashboard',
            't_id' => $team_id,
            'type' => $this->type
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

    /**
     * checks if a user with this username exists
     * @param   string  $user
     * @return  boolean
     */

    private function user_exists($user) {
        if (!$user) {
            return false;
        }

        $query =
        "SELECT `user_id`
        FROM `users`
        WHERE `username` = ?";

        $res = $this->db->query($query, $user)->fetchArray();
        return !empty($res);
    }

    /**
     * checks if a user with this email exists
     * @param   string  $email
     * @return  boolean
     */

    private function email_exists($email) {
        if (!$email) {
            return false;
        }

        $query =
        "SELECT `user_id`
        FROM `users`
        WHERE `email` = ?";

        $res = $this->db->query($query, $email)->fetchArray();
        return !empty($res);
    }
}

?>