<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/services/MessageService.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/util/TECDB.php');

class User {
    protected $id;
    protected $role;
    protected $db;
    protected $username;
    protected $email;

    public function __construct($id, $role='user') {
        $this->id = $id;
        if (!$this->id) {
            $this->role='user';
            $this->db = 0;
            $this->username = '';
            $this->email = '';
            return;
        }

        $this->role = $role;
        $this->db = new TECDB();
        
        $query = 
        "SELECT `username`, `email`
        FROM `users`
        WHERE `user_id` = ?";
        $res = $this->db->query($query, $this->id)->fetchArray();

        $this->set_username($res['username']);
        $this->set_email($res['email']);

        /*
        $user = get_user_by('ID', $id);
        $this->username = $user->user_login;
         */
    }

    /**
     * Get the user's id
     * 
     * @return  int
     */
    
    public function get_id() {
        return $this->id;
    }

    /**
     * Get the user's username
     * @return  string
     */

    public function get_username() {
        return $this->username;
    }

    /**
     * Set the user's username
     * @param   string  $username
     */

    private function set_username($username) {
        $this->username = $username;
    }

    /**
     * Gets the user's email
     * @return  string
     */

    public function get_email() {
        return $this->email;
    }

    /**
     * Sets the user's email
     * @param   string  $email
     */

    private function set_email($email) {
        $this->email = $email;
    }

    /**
     * Get the user's role
     * @return  string
     */

    public function get_role() {
        return $this->role;
    }

    /**
     * Gets all users that follow this user
     */

    public function get_followers() {

    }

    /**
     * Gets all users that this user follows
     */

    public function get_following() {

    }

    /**
     * Gets all posts that this user made
     */

    public function get_posts() {

    }

    /**
     * Generates a feed for the user
     */

    public function generate_feed() {

    }

    /**
     * Gets a meta value for the user from key
     * @param   string  $key
     * @return  string
     */

    public function get_meta($meta_key) {
        if (!$this->db) {
            return;
        }

        $query = 
        'SELECT `meta_value`
        FROM `user_meta`
        WHERE `user_id` = ?
        AND `meta_key` = ?';
        $value = $this->db->query($query, $this->id, $meta_key)->fetchArray();
        return $value['meta_value'] ?? 0;
    }

    /**
     * Sets a {key, value} pair to user's meta
     * @param   string  $key
     * @param   string  $value
     */

    public function set_meta($key, $value) {

    }

    /**
     * Sends a message from this user to another user
     * @param   int $to
     */

    public function send_message($to) {
        $ms = new MessageService();

    }

    /**
     * Gets all users that this user has messaged
     */

    public function get_conversation_history() {

    }

    /**
     * Loads a specific conversation with this user and another
     * @param   int $with
     */

    public function get_conversation($with) {
        
    }

    /**
     * Checks if this user is a player
     * @return  boolean
     */

    public function is_player() {
        if (!$this->id) {
            return false;
        }

        $query = 
        "SELECT COUNT(*) AS rc
        FROM `players`
        WHERE `user_id` = ?";

        $rows = $this->db->query($query, $this->id)->fetchArray();
        return $rows['rc'];
    }

    /**
     * Checks if this user is a team manager
     * @return  boolean
     */

    public function is_team_manager() {
        if (!$this->id) {
            return false;
        }

        $query = 
        "SELECT COUNT(*) AS rc
        FROM `team_managers`
        WHERE `user_id` = ?";

        $rows = $this->db->query($query, $this->id)->fetchArray();
        return $rows['rc'];
    }

    /**
     * Checks if this user is an admin
     * @return  boolean
     */

    public function is_admin() {
        if (!$this->id) {
            return false;
        }

        

    }

    /**
     * Checks if this user is a caster
     * @return  boolean
     */

    public function is_caster() {
        if (!$this->id) {
            return false;
        }

        

    }
}

?>