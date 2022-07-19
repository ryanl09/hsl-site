<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/util/TECDB.php');

class Stats {
    protected $db;

    public function __construct() {
        $this->db = new TECDB();
    }

    /**
     * Gets all stats for a user | array[season_id][game_id][key]
     * @param   int $user_id
     * @return  int|array
     */

    public function get_all($user_id) {
        if (!$user_id) {
            return 0;
        }

        $player = new Player($user_id);
        $seasons = $player->get_all_seasons();


    }

    /**
     * Gets stats for a user for specific game and season
     * @param   int $user_id
     * @param   int $season_id
     * @param   int $game_id
     * @return  int|array
     */

    public function get($user_id, $season_id, $game_id) {
        if (!$user_id || !$season_id || !$game_id) {
            return 0;
        }
    }
}

?>