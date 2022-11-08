<?php

class GImage {
    protected $id;
    protected $db;
    private $url;
    private $owner;
    private $data;

    public function __construct($db, $id){
        $this->db = $db;
        if ($id){
            $loaded = $this->load_img($id);

            if (!$loaded){
                //error loading
            }
        }
    }

    /**
     * loads all image data for a specific id
     * @param   int $id
     * @return  boolean
     */

    private function load_img($id){
        if (!$id){
            return;
        }

        $query=
        "SELECT *
        FROM `uploads`
        WHERE `id` = ?";

        $res = $this->db->query()->fetchArray();

        if (empty($res)){
            return false;
        }

        $this->id = $id;
        $this->owner = $res['user_id'];
        $this->url = $res['url'];

        $this->data = get_data();
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

        $res = $db->query($query, $this->id)->fetchAll();
        return $res;
    }

    /**
     * sets data for a image
     * @param   array   $data
     * @return  boolean|int
     */

    public function set_data($data) {
        if (empty($data)){
            return true;
        }

        $this->data = $data;

        $str='';
        $d=[];
        for ($i = 0; $i < count($data); $i++){
            $d[] = $data['x'];
            $d[] = $data['y'];
            $d[] = $data['width'];
            $d[] = $data['height'];
            
            $str .= '(?, ?, ?, ?)' . ($i < count($data)-1 ? ', ' : '');
        }

        $query=
        "INSERT INTO `graphics_data`
        VALUES $str";

        $res = $this->db->query($query, $d)->numRows();
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
}

?>