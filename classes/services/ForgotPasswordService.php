<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/classes/security/AuthToken.php');
require_once($path . '/classes/security/PasswordHash.php');
require_once($path . '/classes/services/VerifyService.php');

class ForgotPasswordService extends VerifyService {
    public function __construct(){
        parent::__construct(array());
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

        /*
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

        $headers = 'From: TEC Esports <no-reply@tecesports.com>' . "\r\n";
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $sent = mail($email, $subject, $body, $headers);

        $sent = mail('ryan@theesportcompany.com', 'hi', 'hi');*/

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

        $email='';
        if (session_id() && isset($_SESSION['req_email'])){
            $email=$_SESSION['req_email'];        
        }

        $query=
        "SELECT LOWER(`req_key`), `req_time`
        FROM `forgot_req`
        WHERE `email` = ?";

        $res = $this->db->query($query, $email)->fetchArray();

        if(isset($res['req_key']) && $res['req_key']) {
            $equals = strcmp($res['req_key'], strtolower($token)) === 0;
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
        if(!$pass){
            return false;
        }

        $email='';
        if (session_id() && isset($_SESSION['req_email'])){
            $email=$_SESSION['req_email'];
        }
        else {
            return false;
        }

        $ph = new PasswordHash($password);
        $pass = $ph->create();

        $query=
        "UPDATE `users`
        SET `password` = ?
        WHERE `email` = ?";
        $res = $this->db->query($query, $pass, $email)->affectedRows();

        return $res;
    }
}

?>