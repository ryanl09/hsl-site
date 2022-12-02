<?php

require_once('Round.php');

//used for playoffs
class Tournament {
    private $db;
    private $title;
    private $id;
    private $game;

    public function __construct($db, $id, $load=false){
        $this->db = $db;
        $this->id = $id;
        $this->title = '';
        $this->game = 0;
        if($load){
            $this->load_data();
        }
    }

    /**
     * creates rounds
     */

    public function create_rounds($teams, $start_day){
        $tc = count($teams);
        if ($tc % 2 !== 0){
            $rand = rand(0, $tc - 1);
            array_splice($teams, $rand, 0, 0);
        }

        $rounds = array();
        while ($tc / 2 > 1){
            $h = 0;
            while ($h < $tc / 2){
                $r = new Round($this->db, 0, $this->to_array());
                if ($tc === count($teams)){
                    $home = $teams[$h];
                    $away = $teams[$tc - $h - 1];
                    $time = '00:00:00';
                    $r->insert($home, $away, $time, $date, $this->game);
                    continue;
                }
    
                $r->insert_empty($this->game);
                $h++;
            }
            $tc /= 2;
        }
    }

    /**
     * loads data
     */

    public function load_data(){
        $query=
        "SELECT `title`, `game_id`
        FROM `tournaments`
        WHERE `id` = ?";
        $res = $this->db->query($query, $this->id)->fetchArray();
        if (!empty($res)){
            $this->title = $res['title'];
            $this->game = $res['game_id'];
        }
    }

    /**
     * converts this to an array (for round)
     * @return  array
     */

    public function to_array() {
        return array(
            'title' => $this->title,
            'id' => $this->id,
            'game' => $this->game
        );
    }

    /**
     * gets all rounds for the tournament
     * @return  array
     */

    public function get_rounds() {
        if (!$this->id){
            return [];
        }

        $r = new Round($this->db, 0, $this->to_array());
        $rounds = [$r];
        while ($r){
            $r = $r->get_next();
            $rounds[] = $r;
        }

        if (!end($rounds)){
            array_pop($rounds);
        }

        return $rounds;
    }

    /**
     * static functions
     */

    public static function create($db, $title, $game, $div, $teams=[], $start_day){
        if (!$title || !$game){
            return 0;
        }

        $query=
        "INSERT INTO `tournaments` (`title`, `game_id`)
        VALUES (?, ?)";

        $id = $db->query($query, $title, $game)->lastInsertID();
        $type = gettype($teams);

        if (empty($teams) || $type==='integer'){
            require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/general/Standings.php');
            $stnd = Standings::get($db, $game, $div);

            if ($type==='integer'){
                $stnd = array_slice($stnd, 0, $teams);
            }
            $teams = $stnd;
        }

        $t = new Tournament($db, $id, true);
        $t->create_rounds($teams, $start_day);

        return $id;
    }
}

?>