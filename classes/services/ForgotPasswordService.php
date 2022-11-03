<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/classes/security/AuthToken.php');
require_once($path . '/classes/security/PasswordHash.php');
require_once($path . '/classes/services/EmailService.php');
require_once($path . '/classes/services/LogoutService.php');
require_once($path . '/classes/services/VerifyService.php');

class ForgotPasswordService extends VerifyService {
    public function __construct($db){
        parent::__construct($db, array());
    }

    /**
     * send code to user email and put request in database
     * @param   string  $email
     * @return  string
     */

    public function send_code($email) {
        $email=trim($email);
        if (!$email){
            return array(
                'status' => 0,
                'error' => 'Missing email'
            );
        }

        $query=
        "SELECT `email`
        FROM `users`
        WHERE `email` = ? OR `username` = ?";

        $res = $this->db->query($query, $email, $email)->fetchArray();

        if (!isset($res['email']) || !$res || !$res['email']){
            return array(
                'status' => 0,
                'error' => 'No account with that email address exists'
            );
        }

        $email = $res['email'];

        $query=
        "SELECT `req_time`
        FROM `forgot_req`
        WHERE `email` = ?;";

        $res = $this->db->query($query, $email)->fetchArray();
        if (!empty($res)) {
            $time = strtotime($res['req_time']);
            $now = time();

            if ($now-$time <= 300) {
                return array(
                    'status' => 0,
                    'error' => 'You must wait ' . $now-$time . ' more seconds to request again'
                );
            }
        }

        $at = new AuthToken(40);
        $token = strtolower($at->create());

        $query=
        "INSERT INTO `forgot_req` (`email`, `req_key`, `req_time`, `used`)
        VALUES (?, ?, now(), 0)
            ON DUPLICATE KEY UPDATE
            req_key = ?,
            req_time=now();";

        $res=$this->db->query($query, $email, $token, $token)->affectedRows();
        $_SESSION['req_email']=$email;

        $query=
        "SELECT `username`
        FROM `users`
        WHERE `email` = ?";
        $user = $this->db->query($query, $email)->fetchArray();
        $user=$user['username'];

        
        $link = 'https://tecesports.com/forgot/' . $token;

        $subject = "Reset Password";

        $body = '<table style="width:100%;">';
        $body .= '<tbody>';
        $body .= '<tr><td><img src="https://tecesports.com/images/tec-black.png" width="100" height="100" alt="TEC"></td></tr>';
        $body .= '<tr><td>Hi '.$user.',</td></tr>';
        $body .= '<tr><td><a href="'.$link.'">Click here to reset your password</a></td></tr>';
        $body .= '<tr><td>This link will be active for 15 minutes.</td></tr>';
        $body .= '<tr><td>If you did not request this, you may ignore it.</td></tr>';
        $body .= '</tbody>';
        $body .= "</table>";

        $es = new EmailService();
        $sent = $es->send($email, $subject, $body);

        /*
        $headers = 'From: TEC Esports <no-reply@tecesports.com>' . "\r\n";
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $sent = mail($email, $subject, $body, $headers); */

        if ($sent) {
            return array(
                'status' => 1,
                'success' => 'Link sent! Please check your email to continue'
            );
        }

        return array(
            'status' => 0,
            'error' => 'Could not send. Please try again'
        );
    }

    /**
     * accept key or not
     * @param   string  $token
     * @return  boolean
     */

    public function verify($token){
        if (!$token){
            return false;
        }

        $token=strtolower($token);

        $query=
        "SELECT LOWER(`req_key`) as req_key, `req_time`, `email`
        FROM `forgot_req`
        WHERE LOWER(`req_key`) = ?";

        $res = $this->db->query($query, $token)->fetchArray();

        if(isset($res['req_key']) && $res['req_key']) {
            $equals = $res['req_key']===$token;
            $time = time()-strtotime($res['req_time']) <= 900;
            return $equals && $time;
        }
        return false;
    }

    /**
     * reset password
     * @param   string  $password
     * @return  boolean
     */

    public function reset($password, $login=true){
        if(!$password){
            return false;
        }

        if (!session_id() || !isset($_SESSION['forgot_pass_token'])){
            return false;
        }

        $token=$_SESSION['forgot_pass_token'];

        $query=
        "SELECT LOWER(`email`) as email
        FROM `forgot_req`
        WHERE `req_key` = ?";
        $res=$this->db->query($query,$token)->fetchArray();
        if(!isset($res['email'])){
            return false;
        }
        $email=$res['email'];

        $ph = new PasswordHash($password);
        $pass = $ph->create();

        $query=
        "UPDATE `users`
        SET `password` = ?
        WHERE `email` = ?";
        $res = $this->db->query($query, $pass, $email)->affectedRows();

        if ($res){
            LogoutService::logout(false);
        }

        return $res;
    }
}

?>