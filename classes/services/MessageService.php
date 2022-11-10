<?php 

class MessageService {
    private $db;
    private $id;

    public function __construct($db) {
        $this->db = $db;
        if (session_id() && isset($_SESSION['user'])){
            $this->id = $_SESSION['user']->get_id();
        }
    }

    /**
     * gets user's messages since this time
     * @param   date    $time
     * @return  array
     */

    public function get_since($time){
        if (!$time || $this->id){
            return [];
        }

        $query=
        "SELECT *
        FROM `messages`
        WHERE `id_to` = ?
            AND `time_sent` >= ?
            AND `time_sent` <= NOW()";

        $res = $this->db->query($query, $this->id, $time)->fetchAll();
        return $res;
    }

    /**
     * sends a message
     * @param   int $to
     * @param   string  msg
     * @return  int
     */
    
    public function send($to, $msg){
        if (!$to || !$msg || !$this->id){
            return false;
        }

        $query=
        "INSERT INTO `messages` (`id_from`, `id_to`, `message`)
        VALUES (?, ?, ?, ?)";

        $res = $this->db->query($query, $this->id, $to, $msg)->lastInsertID();
        return $res;
    }

    /**
     * gets convo history of user
     * @return  array
     */

    public function get_convos() {
        if (!$this->id){
            return [];
        }

        $query=
        "SELECT DISTINCT messages.id_from, messages.id_to
        FROM `messages`
        INNER JOIN `users`
            ON (users.user_id = messages.id_from AND messages.id_from <> ?)
        WHERE messages.id_to = ? OR messages.id_from = ?";

        $res = $this->db->query($query, $this->id, $this->id)->fetchAll();
        return $res;
    }
}

?>