<?php
    $path = $_SERVER['DOCUMENT_ROOT'];
    require_once($path . '/classes/util/tecdb.php');
    require_once($path . '/classes/util/Sessions.php');

    class Announcements {
        private function __construct() {

        }

        static function get_all() {
            $db = new tecdb();

            $query = 'SELECT a.user_id, a.title, a.body, a.time, users.name, users.pfp_url
                    FROM announcements a 
                    INNER JOIN users ON users.user_id = a.user_id
                    ORDER BY a.time DESC';

            $res = $db->query($query)->fetchAll();
            
            return $res;
        }

        static function create($title, $body) {
            $id = 0;
            if (!isset($_SESSION['user'])) {
                return false;
            }
            $id = $_SESSION['user']->get_id();

            $db = new tecdb();

            $query = 'INSERT INTO announcements
                        (`user_id`, title, body)
                        VALUES (?, ?, ?)';

            $res = $db->query($query, $id, $title, $body)->lastInsertID();
            return $res;
        }

        
    }
?>