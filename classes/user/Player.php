<?php

require_once('User.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/general/Season.php');

class Player extends User {
    public function __construct($db, $id){
        parent::__construct($db, $id, 'player');
    }

    /**
     * Retrieves a player's total stats for a specified season
     * @param   int $season
     * @return  array
     */

    public function get_stats($season = 'current') {
        if (!$this->db) {
            return;
        }

        if ($season ==='current') {
            $season = Season::get_current();
        }

        $query = 
        'SELECT * 
        FROM stats 
        WHERE user_id = ? 
        AND season_id = ?';

        $stats = $this->db->query($query, $this->id, $season)->fetchArray();
        return $stats ?? array();
    }

    public function get_stats_from_event($event_id) {
        
    }

    public function get_schedule() {
        if (!$this->db) {
            return;
        }

        
    }

    public function get_vods() {

    }

    public function get_teams() {

    }

    public function get_grade_level() {

    }

    public function add_to_team($team) {

    }

    public function remove_from_team($team) {

    }

    public function is_on_team($team) {

    }

    /**
     * static functions
     */

    public static function from_username($db, $username) {
        if ($username && trim($username)) {
            $username = tec::safe($username);
            $id = $db->query('SELECT * FROM players WHERE username = ?', $username)->fetchArray();
            return new Player($id['user_id'] ?? 0);
        }
        return new Player(0);
    }
}

?>