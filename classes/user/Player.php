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
            $season = Season::get_current($this->db);
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
            return new Player($db, $id['user_id'] ?? 0);
        }
        return new Player($db, 0);
    }

    /**
     * get all players: fname,lname,ign,school
     */

    public static function get_all($db) {
        $query=
        "SELECT DISTINCT users.name, user_igns.ign, teams.team_name
        FROM `users`
        INNER JOIN `user_igns`
            ON users.user_id = user_igns.user_id
        INNER JOIN `teams`
            ON users.team_id = teams.id
        WHERE users.role = \"player\"";
        $res = $db->query($query)->fetchAll();
        return $res;
    }
}

?>