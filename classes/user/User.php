<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/classes/services/MessageService.php');
require_once($path . '/classes/user/Admin.php');
require_once($path . '/classes/user/Caster.php');
require_once($path . '/classes/user/Player.php');
require_once($path . '/classes/user/Production.php');
require_once($path . '/classes/user/Staff.php');
require_once($path . '/classes/user/TeamManager.php');
require_once($path . '/classes/util/TECDB.php');

class User {
    protected $id;
    protected $role;
    protected $db;
    protected $username;
    protected $email;
    protected $name;
    protected $pronouns;
    protected $pfp_url;

    public function __construct($id, $role='user') {
        $this->id = $id;
        if (!$this->id) {
            $this->role='user';
            $this->db = 0;
            $this->username = 'Guest';
            $this->email = '';
            $this->name = '';
            $this->pronouns = '';
            $this->pfp_url = '';
            return;
        }

        $this->role = $role;
        $this->db = new TECDB();
        
        $query = 
        "SELECT `username`, `email`, `pfp_url`, `user_id`, `name`, `pronouns`
        FROM `users`
        WHERE `user_id` = ?";
        $res = $this->db->query($query, $this->id)->fetchArray();


        $this->set_username($res['username']);
        $this->set_email($res['email']);
        $this->set_name($res['name']);
        $this->set_pronouns($res['pronouns']);
        $this->pfp_url = $res['pfp_url'] ? $res['pfp_url'] : '/images/user.png';

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
     * Gets user's name
     * @return  string
     */

    public function get_name() {
        return $this->name;
    }

    /**
     * Sets user's name
     * @param   string  $name
     */

    private function set_name($name) {
        $this->name = $name;
    }

    /**
     * Gets user's pronouns
     * @return  string
     */

    public function get_pronouns() {
        return $this->pronouns;
    }

    /**
     * Sets user's pronouns
     * @param   string  $pronouns
     */

    private function set_pronouns($pronouns) {
        $this->pronouns = $pronouns;
    }

    /**
     * Gets user's profile image
     */

    public function profile_image() {
        return $this->pfp_url;
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
            return false;
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

    /**
     * Static functions
     */

     /**
      * Finds user's id from their username
      * @param  string  $username
      * @return int
      */
    
    public static function find_id($username) {
        if (!$username) {
            return 0;
        }

        $db = new tecdb();

        $query =
        "SELECT `user_id`
        FROM `users`
        WHERE `username` = ?";

        $res = $db->query($query, $username)->fetchArray();

        return $res['user_id'] ?? 0;
    }

    /**
     * Determines which class is needed for a specific user
     * @param   int $user_id
     * @return  Player|Admin|Caster|Production|Staff|TeamManager
     */

    public static function get_class_instance($user_id, $username='') {
        if (!$user_id && !$username) {
            return new User($user_id);
        }

        $db = new tecdb();

        $sql = 
        "SELECT `role`, `user_id`
        FROM `users`
        WHERE `user_id` = ? OR `username` = ?";

        $res = $db->query($sql, $user_id, $username)->fetchArray();

        if (!empty($res['user_id'])) {
            $user_id = $res['user_id'];
        }
        
        if (empty($res['role'])) {
            return new User($user_id);
        }

        $role = $res['role'];
        switch ($role) {
            case 'admin':
                return new Admin($user_id);
                break;
            case 'caster':
                return new Caster($user_id);
                break;
            case 'player':
                return new Player($user_id);
                break;
            case 'production':
                return new Production($user_id);
                break;
            case 'staff':
                return new Staff($user_id);
                break;
            case 'team_manager':
                return new TeamManager($user_id);
                break;
            default:
                return new User($user_id);
        }
    }

    /**
     * Gets the badges for a user's page
     * @param   int $user_id
     * @return  array
     */

    public static function get_badges($user_id) {
        if (!$user_id) {
            return [];
        }

        $db = new tecdb();

        $query = 
        "SELECT badge_types.url
        FROM `badges`
        INNER JOIN `badge_types`
            ON badge_types.id = badges.badge_id
        WHERE `user_id` = ?";

        $res = $db->query($query, $user_id)->fetchAll();

        return $res;
    }

    /**
     * Gets all profile data for a user
     * @param   int $user_id
     * @return  array
     */

    public static function get_profile_data($user_id) {
        if (!$user_id) {
            return [];
        }

        $db = new tecdb();

        $query =
        "SELECT *
        FROM `user_profile_display`
        WHERE `user_id` = ?";

        $res = $db->query($query, $user_id)->fetchArray();
        return $res;
    }
}

?>