<?php

interface IEvent {
    public function get_id();
    public function get_date();
    public function set_date($date);
    public function get_time();
    public function set_time($time);
    public function get_winner();
}

?>