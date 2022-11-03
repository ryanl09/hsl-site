<?php
    $path = $_SERVER['DOCUMENT_ROOT'];
    require_once($path . '/classes/util/Sessions.php');

    class Announcements {
        private function __construct() {

        }

        static function get_all($db) {
            $query = 'SELECT a.user_id, a.title, a.body, a.time, users.name, users.pfp_url, a.announcement_id
                    FROM announcements a 
                    INNER JOIN users ON users.user_id = a.user_id
                    ORDER BY a.time DESC';

            $res = $db->query($query)->fetchAll();
            
            return $res;
        }

        static function create($db, $title, $body) {
            $id = 0;
            if (!isset($_SESSION['user'])) {
                return false;
            }
            $id = $_SESSION['user']->get_id();

            $query = 'INSERT INTO announcements
                        (`user_id`, title, body)
                        VALUES (?, ?, ?)';

            $res = $db->query($query, $id, $title, $body)->lastInsertID();
            return $res;
        }

        static function delete($db, $announcement_id) {
            if (!isset($_SESSION['user'])) {
                return false;
            }
            $query = 'DELETE FROM `announcements`
                        WHERE `announcement_id` = ?';

            $res = $db->query($query, $announcement_id)->lastInsertID();
            return $res;
        }
    }
?>