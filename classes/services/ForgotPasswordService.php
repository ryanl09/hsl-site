<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/classes/security/AuthToken.php');
require_once($path . '/classes/security/PasswordHash.php');
require_once($path . '/classes/services/VerifyService.php');

class ForgotPasswordService extends VerifyService {
    public function __construct(){

    }

    /**
     * send code to user email and put request in database
     * @param   string  $email
     * @return  string
     */

    public function send_code($email) {
        if (!$email){
            return array(
                'error' => 'Missing email'
            );
        }

        $query=
        "SELECT EXISTS(
            SELECT 1
            FROM `users`
            WHERE `email` = ?
        );";

        $res = $this->db->query($query, $email)->fetchArray();

        if (!$res){
            return array(
                'error' => 'No account with that email address exists'
            );
        }

        $query=
        "SELECT `req_time`
        FROM `forgot_req`
        WHERE `email` = ?;"

        $res = $this->db->query($query, $email)->fetchArray();
        if (!empty($res)) {
            $time = strtotime($res['req_time']);
            $now = time();

            if ($now-$time <= 200) {
                return 'You must wait ' . $now-$time . ' more seconds to request again';
            }
        }

        $at = new AuthToken(40);
        $token = $at->create();

        $query=
        "INSERT INTO `forgot` (`email`, `req_key`, `req_time`, `used`)
        VALUES (?, ?, now(), 0)
            ON DUPLICATE KEY UPDATE
            req_key = ?,
            req_time=now();"

        $res=$this->db->query($query, $email, $token, $token)->affectedRows();
        $_SESSION['req_email']=$email;
        return $token;
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
        "SELECT LOWER(`req_key`)
        FROM `forgot_req`
        WHERE `email` = ?";

        $res = $this->db->query($query, $email)->fetchArray();

        if(isset($res['req_key']) && $res['req_key']) {
            return strcmp($res['req_key'], strtolower($token)) === 0;
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