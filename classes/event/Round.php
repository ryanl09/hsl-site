<?php

require_once('Event.php');

class Round {
    private $r_num;
    private $t;
    private $db;
    private $max = 0;

    public function __construct($db, $r_num, $t){
        $this->db = $db;
        $this->r_num = $r_num;
        if (!$this->r_num){
            $this->max = get_max();
            $this->r_num = $this->max + 1;
        }
        $this->t = $t;
    }

    /**
     * inserts event and round
     */

    public function insert($home, $away, $time, $date, $game){
        $e = Event::create($this->db, $home, $away, $time, $date, $game);

        $t_id = $this->t['id'];

        $query=
        "INSERT INTO `tournament_rounds` (`round_num`, `tournament_id`, `event_id`)
        VALUES (?, ?, ?)";

        $r = $this->db->query($query, $this->r_num, $t_id, $e)->affectedRows();
        return $r > 0;
    }

    /**
     * inserts an empty round
     * @param   int $game
     * @return  boolean
     */

    public function insert_empty($game) {
        $t_id = $this->t['id'];

        $query=
        "INSERT INTO `tournament_rounds` (`round_num`, `tournament_id`, `event_id`)
        VALUES (?, ?, 0)";

        $r = $this->db->query($query, $this->r_num, $t_id)->affectedRows();
        return $r > 0;
    }

    /**
     * get prev round
     * @return  boolean|Round
     */

    public function get_prev() {
        if ($this->r_num <= 1){
            return false;
        }

        $prev = $this->r_num - 1;
        return new Round($this->db, $prev, $this->t);
    }

    /**
     * get next round
     * @return  boolean|Round
     */

    public function get_next() {
        if (!$this->has_next()){
            return false;
        }

        $next = $this->r_num + 1;
        return new Round($this->db, $next, $this->t);
    }

    /**
     * sees if there is next round
     * @return  boolean
     */

    private function has_next() {
        if (!$this->max){
            $this->max = $this->get_max();
            if (!$this->max){
                return false;
            }
        }

        return $this->r_num < $this->max;
    }

    private function get_max() {
        $query=
        "SELECT MAX(`round_num`) AS r_max
        FROM `tournament_rounds`
        WHERE `tournament_id` = ?";
        
        $res = $this->db->query($query, $this->t['id'])->fetchArray();
        if (empty($res)){
            return 0;
        }

        return $res['r_max'];
    }
}

?>