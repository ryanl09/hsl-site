<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/util/tecdb.php');

class Stats {
    protected $db;

    public function __construct() {
        $this->db = new tecdb();
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

    /**
     * Gets all stat columns for an event
     * @param   int $game_id
     * @return  array
     */

    public function get_cols($event_id) {
        if (!$event_id) {
            return [];
        }

        $query =
        "SELECT stat_cols.id, stat_cols.name
        FROM `stat_cols`
        INNER JOIN `events`
            ON events.id = ?
        WHERE stat_cols.game_id = events.event_game
        ORDER BY stat_cols.id ASC";

        $res = $this->db->query($query, $event_id)->fetchAll();
        return $res;
    }

    /**
     * Gets stats for each player for a team from a certain event
     * @param   int $event_id
     * @param   int $subteam_id
     * @return  array
     */

    public function get_stats($event_id, $subteam_id) {
        if (!$event_id || !$subteam_id) {
            return [];
        }

        $query = 
        "SELECT stats.user_id, stats.stat_id, stats.stat_value
        FROM `stats`
        INNER JOIN `stat_cols`
            ON stat_cols.id = stats.stat_id
        WHERE stats.user_id IN (
            SELECT users.user_id
            FROM `users`
            INNER JOIN `subteams`
                ON subteams.id = ?
            INNER JOIN `player_seasons`
                ON player_seasons.user_id = users.user_id AND player_seasons.subteam_id = ? AND player_seasons.season_id = ?
            )
        AND stats.event_id = ?";

        $res = $this->db->query($query, $subteam_id, $subteam_id, Season::get_current(), $event_id)->fetchAll();
        return $res;
    }

    /**
     * updates or inserts a new stat
     * @param   int $user_id
     * @param   int $event_id
     * @param   int $stat_id
     * @param   int $stat_value
     * @return  boolean
     */

    public function add($user_id, $event_id, $stat_id, $stat_value) {
        $query = 
        "INSERT INTO `stats` (`user_id`, `event_id`, `stat_id`, `stat_value`) 
        VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE `stat_value` = ?";

        $db = new tecdb();
        $ret = $db->query($query, $user_id, $event_id, $stat_id, $stat_value, $stat_value)->lastInsertID();
        return $ret;
    }
}

?>