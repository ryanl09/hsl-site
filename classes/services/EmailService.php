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
        $this->host = 'smtp.gmail.com';
        $user='ryan@theesportcompany.com';
        $pass='P@ssword123*';
        //$user = "AKIAYKMGARIXWCLUQZDD";
        //$pass = "BFhVUsVHhQjcoOAZ8sQSPvLQijquDRlhJluBgvynOYTa";

        //$user = "AKIAYKMGARIXY7LKPHMO";
        //$pass = "BCaXA+wiPddair9kuKgBjgn1iAwRt9hrisIP3RLntDVt";
        $this->mail = new PHPMailer(true);
        try{
            $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;
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
            $this->mail->SMTPDebug = 4;
            $this->mail->setFrom('no-reply@tecesports.com', 'TEC Esports');
            //$this->mail->AuthType = 'LOGIN';
            $this->mail->Host = $this->host;
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
            $this->mail->addAddress("ryan@theesportcompany.com", 'Ryan');
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