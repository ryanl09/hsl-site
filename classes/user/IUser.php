<?php

interface IUser {
    public function get_id();
    public function get_username();
    public function get_role();
    public function get_followers();
    public function get_following();
    public function get_posts();
    public function generate_feed();
    public function get_meta();
    public function set_meta();

    public function is_player();
    public function is_team_manager();
    public function is_admin();
    public function is_caster();
}

?>