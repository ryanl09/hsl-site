<?php
    $path = $_SERVER['DOCUMENT_ROOT'];
    require_once($path . '/classes/util/tecdb.php');
    require_once($path . '/classes/services/EmailService.php');

    public class ConfirmEmailService {
        protected $db;

        public function __construct($db){
            $this->db = $db;
        }

        public function send_confirmation_email($email) {
            $query=
            "SELECT `name`, `email`, `activation_key`
            FROM `users`
            WHERE `email` = ? OR `username` = ?";

            $res = $this->db->query($query, $email, $email)->fetchArray();
            $auth_key = $res['activation_key'];
            $fname = strtok($res['name'], " ");

            $link = '';

            $subject = "TECEsports Activate Account";

            $body = '<div style="width: 600px; border: 2px solid #E9EBF6; margin: auto; font-size: 16px; color: #555555;">';
            $body .= '<h1 style="margin: 0; padding: 8px; background-color: #E9EBF6; text-align: center;">';
            $body .= 'Hello, <?=$fname;?>!';
            $body .= '</h1>';
            $body .= '<div style="overflow: hidden; padding: 8px; padding-top: 0; background-color: #F5F6FB;">';
            $body .= '<p>You are receiving this email because you (or someone pretending to be you!) has signed up for a new account at TECEsports.</p>'
            $body .= '<p>If you would like to verify this email account (and you must in order to use the system), please <a href="https://tecesports.com/activate/<?=$auth_key;?>">click this link</a>.</p>'
            $body .= '<p>If you don\'t know what this is about, or you don\'t want the account, simply do nothing.</p>';
            $body .= '<p>The quick login link above is a one-time access pass to your account.  Please use the link to verify your email address and complete your account signup.</p>';
            $body .= '<br />';
            $body .= '<p>Thanks!</p>';
            $body .= '<p>-TECEsports</p>';
            $body .= '</div>';
            $body .= '</div>';

            $es = new EmailService();
            $sent = $es->send($email, $subject, $body);

            if ($sent) {
                return array(
                    'status' => 1,
                    'success' => 'Link sent! Please check your email to verify'
                );
            }
    
            return array(
                'status' => 0,
                'error' => 'Could not send. Please try again'
            );
        }

        public function verify_email($auth_token) {
            if (!$auth_token) {
                return false;
            }

            $query = "SELECT `activation_key`, `actiavted`
            FROM `users`
            WHERE `activation_key` = ?";

            $res = $this->db->query($query, $auth_token)->fetchArray();

            if (isset($res['activation_key'])) {
                if($res['activation_key'] === $auth_token && $res['activated'] == 0) {
                    $query = "UPDATE `users`
                    SET `activated` = 1
                    WHERE `activation_key` = ?";

                    $row = $this->db->query($query, $auth_token)->affectedRows();
                    return $row;
                }
            }
        }
    }
?>