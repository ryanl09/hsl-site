<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/util/tecdb.php');

abstract class TeamAbstract {
    protected $db;
    protected $id;

    public function __construct($db, $id) {
        $this->id = $id;
        $this->db = $db;
    }

    public abstract function get_logo();
    public abstract function register($season);

    /**
     * Gets id of team
     * @return  int
     */

    public function get_id() {
        return $this->id;
    }
}

?>