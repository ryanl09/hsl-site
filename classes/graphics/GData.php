<?php
    $path = $_SERVER['DOCUMENT_ROOT'];
    require_once($path . '/classes/util/tecdb.php');

    class GData {
        private function __construct() {

        }

        public static function get_boxes() {
            $db = new tecdb();

            $query = 'SELECT gd.id, gd.upload_id, gd.x, gd.y, gd.width, gd.height
                        FROM graphics_data gd
                        INNER JOIN uploads u ON u.id = gd.upload_id';

            $res = $db->query($query)->fetchAll();
                
            return $res;
        }

        public static function set_boxes($upload_id, $x, $y, $width, $height) {
            if (!isset($_SESSION['user'])) {
                return false;
            }

            $db = new tecdb();

            $query = 'INSERT INTO graphics_data
                        (`id`, upload_id, x, y, width, height))
                        VALUES (?, ?, ?, ?, ?, ?)';

            $res = $db->query($query, $upload_id, $x, $y, $width, $height)->lastInsertID();
            return $res;
        }
    }
?>