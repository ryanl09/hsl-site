<?php 

class MessageService {
    private $db;
    private $id;
    const LOAD = 10;

    public function __construct($db) {
        $this->db = $db;
        if (session_id() && isset($_SESSION['user'])){
            $this->id = $_SESSION['user']->get_id();
        }
    }

    /**
     * test id
     */

    public function get_id(){
        return $this->id;
    }

    /**
     * gets user's messages since this time
     * @param   date    $time
     * @return  array
     */

    public function get_since($time, $now){
        if (!$time || !$this->id){
            return [];
        }

        $query=
        "SELECT *
        FROM `messages`
        WHERE `id_to` = ?
            AND `time_sent` <= ?
            AND `time_sent` >= ?";

        $res = $this->db->query($query, $this->id, $now, $time)->fetchAll();
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
        VALUES (?, ?, ?)";

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

        /*
        $query=
        "SELECT DISTINCT users.user_id, CONCAT('https://tecesports.com/uploads/', users.pfp_url) as pfp_url, users.username, messages.time_sent, messages.message
        FROM `messages`
        INNER JOIN `users`
            ON (users.user_id = messages.id_from AND messages.id_from <> ?) OR (users.user_id = messages.id_to AND messages.id_to <> ?)
        WHERE messages.id_to = ? OR messages.id_from = ?";
        */

        $query=
        "SELECT t1.*, CONCAT('https://tecesports.com/uploads/', users.pfp_url) as pfp_url, users.username, users.user_id
        FROM messages t1
        JOIN ( SELECT LEAST(id_from, id_to) user1,
                      GREATEST(id_from, id_to) user2,
                      MAX(time_sent) time_sent
               FROM messages t2
               GROUP BY user1, user2 ) t3  ON t1.id_from IN (t3.user1, t3.user2)
                                          AND t1.id_to IN (t3.user1, t3.user2)
                                          AND t1.time_sent = t3.time_sent
        JOIN users
            ON (users.user_id = t1.id_from AND t1.id_from <> ?) OR (users.user_id = t1.id_to AND t1.id_to <> ?)
        WHERE t1.id_to = ? OR t1.id_from = ?";

        $res = $this->db->query($query, $this->id, $this->id, $this->id, $this->id)->fetchAll();

        foreach ($res as $i => $row){
            if (!$row['pfp_url']){
                $res[$i]['pfp_url'] = 'https://tecesports.com/images/user.png';
            }
        }
        return $res;
    }

    /**
     * gets conversation with another user
     * @param   int $user_id
     * @return  array
     */

    public function get_convo($user_id, $limit){
        if (!$this->id || !$user_id){
            return [];
        }

        $query=
        "SELECT m.id, m.message, m.time_sent, m.time_seen,
            CASE WHEN m.id_from = u_id THEN 1
            ELSE 0
            END AS is_mine
        FROM messages m
        JOIN (SELECT ? as u_id) const
        JOIN (SELECT ? as o_id) const2
        WHERE (m.id_from = u_id AND m.id_to = o_id) OR (m.id_from = o_id AND m.id_to = u_id)
        ORDER BY m.id DESC
        LIMIT ?, ?";

        $res = $this->db->query($query, $this->id, $user_id, $limit, self::LOAD)->fetchAll();
        return $res;
    }
}

?>