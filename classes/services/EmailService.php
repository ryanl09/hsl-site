<?php

$path = $_SERVER['DOCUMENT_ROOT'];

use PHPMAILER\PHPMAILER\PHPMailer;
use PHPMAILER\PHPMAILER\SMTP;
use PHPMAILER\PHPMAILER\Exception;

require_once($path . '/includes/phpmailer/src/Exception.php');
require_once($path . '/includes/phpmailer/src/PHPMailer.php');
require_once($path . '/includes/phpmailer/src/SMTP.php');

class EmailService {
    private $user;
    private $pass;
    private $host = "email-smtp.us-east-2.amazonaws.com";
    private $mail = NULL;

    public function __construct() {
        $this->user = "AKIAYKMGARIXSXM63H7K";
        $this->pass = "BMmWLnQNwyAEtm7rJUMb7vVcV0FKTv2pQsBmuVZNUS6D";

        //2JAWtdBTMQcTveX7qxPzd9JLB+FPsb1igZi3tFjF
        $this->mail = new PHPMailer(true);
        try{
            //$this->mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $this->mail->isSMTP();
            $this->mail->SMTPAuth = true;
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            /*
            $this->mail->SMTPOptions=array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );*/
            //$this->mail->SMTPDebug = 4;
            $this->mail->setFrom('no-reply@tecesports.com', 'TEC Esports');
            $this->mail->AuthType = 'LOGIN';
            $this->mail->Host = $this->host;
            $this->mail->Hostname = 'tecesports.com';
            $this->mail->Username = $this->user;
            $this->mail->Password = $this->pass;
            $this->mail->Port = 587;
        } catch (Exception $e){

        }
    }

    public function send($to, $subject, $body){
        if ($this->mail == NULL) {
            return false;
        }

        $sent=false;

        try {
            $this->mail->addAddress($to, '');
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;
            $sent = $this->mail->send();
        } catch (Exception $e){
            $sent=false;
        }

        return $sent;
    }
}