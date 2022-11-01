<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/util/tecdb.php');

class MessageService {
    private $db;

    public function __construct() {
        //parent::__construct($id, $db);
        $this->db = new tecdb();
    }

    /**
     * send a message to a user
     * @param   int $to
     * @return  int
     */

    public function send($to, $from, $message) {
        if ((!$to || !$from) || ($to === $from)){
            return 0;
        }

        $query = 
        "SELECT COUNT(*) AS `user_count`
        FROM `users`
        WHERE `id` = ? or `id` = ?";

        $res = $this->db->query()->fetchArray();
       
        if (intval($res['user_count']) < 2) {
            return 0; //1 or both users do not exist
        }

        $query=
        "INSERT INTO `messages` (`id_from`, `id_to`, `message`)
        VALUES (?, ?, ?)";

        $res = $this->db->query($query, $to, $from, $message)->lastInsertID();
        return $res;
    }


    /**
     * Gets all users and number of unread messages
     */
    public function getUsers($for) {
        // Get Users
        $this->query("SELECT * FROM `users` WHERE `user_id`!=?", [$for]);
        $users = [];
        while ($r = $this->stmt->fetch()) { $users[$r["user_id"]] = $r; }
        
        // Count Unread Messages
        $this->query(
        "SELECT `id_from`, COUNT(*) `ur`
        FROM `messages` WHERE `id_to`=? AND `time_seen` IS NULL
        GROUP BY `id_from`", [$for]);
        while ($r = $this->stmt->fetch()) { $users[$r["id_from"]]["unread"] = $r["ur"]; }

        // Results
        return $users;
    }

    public function getMsg ($from, $to, $limit=30)  {
        // Mark all messages as "read"
        $this->query(
            "UPDATE `messages` SET `time_seen`=NOW()
            WHERE `id_from`=? AND `id_to`=? AND `time_seen` IS NULL", [$from, $to]);
    
        // Get messages
        $this->query(
            "SELECT m.*, u.`user_name` FROM `messages` m
            JOIN `users` u ON (m.`id_from`=u.`user_id`)
            WHERE `id_from` IN (?,?) AND `id_to` IN (?,?)
            ORDER BY `time_sent` DESC
            LIMIT 0, $limit", [$from, $to, $from, $to]);
        return $this->stmt->fetchAll();
    }

    /**
     * delete a message
     * @param   int $message_id
     */

    public function delete($message_id, $user_id) {
        if (!$message_id || !$user_id) {
            return 0;
        }

        $query =
        "DELETE *
        FROM `messages`
        WHERE `id` = ? AND `id_from` = ?";

        $res = $this->db->query($query, $message_id, $user_id)->numRows();
    }

    /**
     * gets a conversation between 2 users
     * @param   int $u
     * @param   int $u2
     * @return  array
     */

    public function get_conversation($u, $u2) {
        if (!$u || !$u2) {
            return [];
        }

        $query=
        "SELECT *
        FROM `messages`
        WHERE (`id_from` = ? AND `id_to` = ?) OR (`id_from` = ? AND `id_to` = ?)";

        $res = $this->db->query($query, $u, $u2, $u2, $u)->fetchAll();
        return $res;
    }
}

?>