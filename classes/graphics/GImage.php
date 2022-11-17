<?php

class GImage {
    protected $id;
    protected $db;
    private $url;
    private $owner;
    private $data;

    public function __construct($db, $id, $load=false){
        $this->db = $db;
        if ($id){
            $loaded = $this->load_img($id, $load);

            if (!$loaded){

            }
        }
    }

    /**
     * loads all image data for a specific id
     * @param   int $id
     * @return  boolean
     */

    private function load_img($id, $load){
        $query=
        "SELECT *
        FROM `uploads`
        WHERE `id` = ?";

        $res = $this->db->query($query, $id)->fetchArray();

        if (empty($res)){
            return false;
        }

        $this->id = $id;
        $this->owner = $res['user_id'];
        $this->url = $res['url'];

        if ($load){
            $this->data = get_data();
        }
        return true;
    }

    /**
     * gets image id
     * @return  int
     */

    public function get_id(){
        return $this->id;
    }

    /**
     * gets image url
     * @return  string
     */

    public function get_url(){
        return $this->url;
    }

    /**
     * gets user_id of image owner
     * @return  int
     */

    public function get_owner(){
        return $this->owner;
    }

    /**
     * gets image data
     * @return  array
     */

    public function get_data() {
        $query=
        "SELECT *
        FROM graphics_data
        WHERE upload_id = ?";

        $res = $this->db->query($query, $this->id)->fetchAll();
        return $res;
    }

    /**
     * sets data for a image
     * @param   array   $data
     * @return  boolean|int
     */

    public function set_data($data) {
        $this->clear_data();
        $this->data = $data;

        if (empty($data)){
            return true;
        }

        $str='';
        $d=[];
        for ($i = 0; $i < count($data); $i++){
            $d[] = $data[$i]['x'];
            $d[] = $data[$i]['y'];
            $d[] = $data[$i]['w'];
            $d[] = $data[$i]['h'];
            
            $str .= "($this->id, ?, ?, ?, ?)" . ($i < count($data)-1 ? ', ' : '');
        }

        $query=
        "INSERT INTO `graphics_data` (`upload_id`, `x`, `y`, `width`, `height`)
        VALUES $str";

        $res = $this->db->query($query, $d)->affectedRows();
        return $res;
    }

    /**
     * removes all data associated with an image
     * @return  boolean
     */

    public function clear_data(){
        if (!$this->id){
            return false;
        }

        $query=
        "DELETE FROM `graphics_data`
        WHERE upload_id = ?";

        $res = $this->db->query($query, $this->id)->affectedRows();
        return $res > 0;
    }

    /**
     * deletes an image
     * @return  boolean
     */

    public function delete() {
        if (!$this->id){
            return false;
        }

        $d = unlink($_SERVER['DOCUMENT_ROOT'] . $this->url);
        if ($d){
            $query=
            "DELETE FROM `uploads`
            WHERE `id` = ?";

            $res = $this->db->query($query, $this->id);
        }
        return $d;
    }

    /**
     * 
     * static functions
     * 
     */

    /**
     * inserts a newly uploaded image to db
     * @param   string  $file
     * @return  int
     */

    public static function insert($db, $file){
        if (!$file){
            return 0;
        }

        if (!session_id() || !isset($_SESSION['user'])){
            return 0;
        }

        $user_id = $_SESSION['user']->get_id();

        $query = 
        "INSERT INTO `uploads` (`url`, `user_id`)
        VALUES (?, ?)";

        $res = $db->query($query, $file, $user_id)->lastInsertID();
        return $res;
    }

    /**
     * displays all
     * @return  array
     */

    public static function display_all($db) {
        $query=
        "SELECT *
        FROM `uploads`";

        $res = $db->query($query)->fetchAll();
        return $res;
    }
}

?>