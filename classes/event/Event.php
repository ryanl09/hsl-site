<?php

require_once('IEvent.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/util/TECDB.php');

class Event implements IEvent {
    private $id;
    private $db;

    public function __construct($id) {
        $this->id = $id;
        $this->db = new TECDB();
    }

    /**
     * Gets the id of event's home team
     * @return  int
     */

    public function get_home_team() {
        if (!$this->id || !$this->db) {
            return 0;
        }

        $query =
        "SELECT `event_home`
        FROM `events`
        WHERE id = ?";

        $home_team = $this->db->query($query, $this->id)->fetchArray();
        return $home_team['event_home'];
    }

    /**
     * Gets the id of event's away team
     * @return  int
     */

    public function get_away_team() {
        if (!$this->id || !$this->db) {
            return 0;
        }

        $query =
        "SELECT `event_away`
        FROM `events`
        WHERE id = ?";

        $home_team = $this->db->query($query, $this->id)->fetchArray();
        return $home_team['event_away'];
    }

    /**
     * Gets event id
     * @return  int
     */

    public function get_id() {
        return $this->id;
    }

    /**
     * Gets event date
     * @return  string
     */
    
    public function get_date() {
        if (!$this->id || !$this->db) {
            return 0;
        }

        $query =
        "SELECT `event_date`
        FROM `events`
        WHERE id = ?";

        $home_team = $this->db->query($query, $this->id)->fetchArray();
        return $home_team['event_date'];
    }

    /**
     * Updates event's date
     * @param   string  $date
     */

    public function set_date($date) {
        if (!$this->id || !$this->db || !$date) {
            return 0;
        }

        $query = 
        "UPDATE `events`
        SET `event_date` = ?
        WHERE id = ?";

        $row = $this->db->query($query, $date, $this->id)->affectedRows();
        return $row;
    }

    /**
     * Gets event time
     * @return  string
     */

    public function get_time() {
        if (!$this->id || !$this->db) {
            return 0;
        }

        $query =
        "SELECT `event_time`
        FROM `events`
        WHERE id = ?";

        $home_team = $this->db->query($query, $this->id)->fetchArray();
        return $home_team['event_time'];
    }

    /**
     * Updates event's time
     * @param   string  $time
     */

    public function set_time($time) {
        if (!$this->id || !$this->db || !$time) {
            return 0;
        }

        $query = 
        "UPDATE `events`
        SET `event_time` = ?
        WHERE id = ?";

        $row = $this->db->query($query, $time, $this->id)->affectedRows();
        return $row;

    }

    /**
     * Gets id of winning team
     */

    public function get_winner() {
        if (!$this->id || !$this->db) {
            return 0;
        }

        $query =
        "SELECT `event_winner`
        FROM `events`
        WHERE id = ?";

        $home_team = $this->db->query($query, $this->id)->fetchArray();
        return $home_team['event_time'];
    }

    /**
     * Gets id of losing team
     */

    public function get_loser() {
        if (!$this->id || !$this->db) {
            return 0;
        }

        
    }

    /**
     * 
     */
}

?>