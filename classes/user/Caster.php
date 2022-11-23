<?php

class Caster extends User {
    public function __construct($db, $id) {
        parent::_construct($db, $id, 'caster');
    }

    /**
     * add event for caster
     * @param   int $event_id
     * @return boolean
     */

    public function add_event($event_id){
        if (!$event_id){
            return false;
        }

        $query=
        "INSERT INTO `match_casters`
        VALUES (?, ?, false, NOW())";
        $res = $this->db->query($query, $this->id, $event_id)->affectedRows();

        return $res>0;
    }

    /**
     * removes event for caster
     * @param   int $event_id
     * @return  boolean
     */

    public function remove_event($event_id, $reason) {
        if (!$event_id) {
            return false;
        }

        $query=
        "DELETE FROM `match_casters`
        WHERE `user_id` = ? AND `event_id` = ?";
        $res = $this->db->query($query, $this->id, $event_id)->affectedRows();

        $res2=1;
        if ($reason){
            $query=
            "INSERT INTO `match_casters_cancel`
            VALUES (?, ?, NOW(), ?)";
            $res2 = $this->db->query($query, $this->id, $event_id, $reason)->affectedRows();
        }

        return $res>0 && $res2>0;
    }

    /**
     * gets all events casters did
     * @return  array
     */

    public function get_events(){
        $query=
        "SELECT `event_id`, `completed`
        FROM `match_casters`
        WHERE `user_id` = ?";

        $res = $this->db->query($query, $this->id)->fetchAll();
        return $res;
    }

    /**
     * gets number of events they casted
     * @return  int
     */

    public function get_count_events() {
        $query=
        "SELECT COUNT(*) as total
        FROM `match_casters`
        WHERE `user_id` = ?";

        $res = $this->db->query($query, $this->id)->fetchArray();
        return $res['total'];
    }

    /**
     * gets all events cancelled
     * @return  array
     */

    public function get_cancelled(){
        $query=
        "SELECT `event_id`, `time`, `reason`
        FROM `match_casters_cancel`
        WHERE `user_id` = ?";

        $res = $this->db->query($query, $this->id)->fetchAll();
        return $res;
    }

    /**
     * gets number of events they casted
     * @return  int
     */

    public function get_count_cancelled() {
        $query=
        "SELECT COUNT(*) as total
        FROM `match_casters_cancel`
        WHERE `user_id` = ?";

        $res = $this->db->query($query, $this->id)->fetchArray();
        return $res['total'];
    }

    /**
     * add games they can cast
     * @param   array   $game_ids
     * @return  boolean
     */

    public function add_games() {
        
    }
}

?>