<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/classes/general/Season.php');
//require_once($path . '/classes/services/MessageService.php');
require_once($path . '/classes/user/Admin.php');
require_once($path . '/classes/user/Caster.php');
require_once($path . '/classes/user/Player.php');
require_once($path . '/classes/user/Production.php');
require_once($path . '/classes/user/Staff.php');
require_once($path . '/classes/user/TeamManager.php');
require_once($path . '/classes/util/tecdb.php');

require_once($path . '/classes/team/Team.php');

class User {
    protected $id;
    protected $role;
    protected $db;
    protected $username;
    protected $email;
    protected $name;
    protected $pronouns;
    protected $pfp_url;
    
    private $team_id;

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
            $this->team_id = 0;
            return;
        }

        $this->role = $role;
        $this->db = new tecdb();
        
        $query = 
        "SELECT `username`, `email`, `pfp_url`, `user_id`, `name`, `pronouns`, `team_id`, `role`
        FROM `users`
        WHERE `user_id` = ?";
        $res = $this->db->query($query, $this->id)->fetchArray();


        $this->set_username($res['username']);
        $this->set_email($res['email']);
        $this->set_name($res['name']);
        $this->set_pronouns($res['pronouns']);
        $this->set_team_id($res['team_id']);
        $this->role = $res['role'];
        $this->pfp_url = $res['pfp_url'] ? $res['pfp_url'] : 'https://tecesports.com/images/user.png';

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
     * Gets user's team id
     * @return  int
     */

    public function get_team_id() {
        return $this->team_id;
    }

    /**
     * Sets user's team id
     * @param   int $team_id
     */

    private function set_team_id($team_id) {
        $this->team_id = $team_id;
    }

    /**
     * Gets user's profile image
     */

    public function profile_image() {
        return $this->pfp_url==='https://tecesports.com/images/user.png' ? $this->pfp_url : 'https://tecesports.com/uploads/' . $this->pfp_url;
    }

    /**
     * Get the user's role
     * @return  string
     */

    public function get_role() {
        return $this->role;
    }

    /**
     * Get's the user's api token (hash activation key + request key)
     * @return  string
     */

    public function get_api_token() {
        if (!$this->db){
            return 'NULL';
        }

        $query=
        "SELECT `activation_key`, `request_key`
        FROM `users`
        WHERE `user_id` = ?";

        $res = $this->db->query($query, $this->id)->fetchArray();

        $a = $res['activation_key'];
        $r = $res['request_key'];

        return sha1($a . $r);
    }

    /**
     * gets user's ign for game
     * @param   int $game_id
     * @return  string
     */

    public function get_ign($game_id){
        if (!$game_id){
            return 'None';
        }

        $query=
        "SELECT `ign`
        FROM `user_igns`
        WHERE `user_id` = ? AND `game_id` = ?";
        $res = $this->db->query($query, $this->id, $game_id)->fetchArray();
        return $res['ign'] ?? '';
    }

    /**
     * sets user's ign
     * @param   int     $game_id
     * @param   string  $ign
     * @return  boolean
     */

    public function set_ign($game_id, $ign){
        if (!$this->id || !$game_id){
            return false;
        }

        $query=
        "INSERT INTO `user_igns`
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE `ign` = ?";

        $res = $this->db->query($query, $this->id, $game_id, $ign, $ign);
        $af = $res->affectedRows();
        $nr = $res->numRows();
        return $af > 0 || $nr > 0;
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

        return $this->role === 'team_manager' || $this->role === 'tm';
    }

    /**
     * Checks if this user is an admin
     * @return  boolean
     */

    public function is_admin() {
        if (!$this->id) {
            return false;
        }

        return $this->role === 'admin';
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
     * Gets all team stuff
     * @return  Team
     */

    public function get_team() {
        return new Team($this->team_id);
    }

    /**
     * gets all subteams this user is on
     * @param   mixed   $season
     * @return  array
     */

    public function get_player_subteams($season='current') {
        if (!$this->id){
            return [];
        }

        if ($season === 'current'){
            $season = Season::get_current();
        }

        $query=
        "SELECT `subteam_id`
        FROM `player_seasons`
        WHERE `user_id` = ? AND `season_id` = ?";

        $res = $this->db->query($query, $this->id, $season)->fetchAll();
        return $res;
    }

    /**
     * Gets team name of the user
     * @return  string
     */

    public function get_team_name() {
        if (!$this->id) {
            return '';
        }

        $query = 
        "SELECT `team_name`
        FROM `teams`
        WHERE `id` = ?";

        $res = $this->db->query($query, $this->team_id)->fetchArray();

        return $res['team_name'] ?? '';
    }

    /**
     * Gets all games the user competes in
     * @return  array
     */

    public function games_competing_in($c_s) {
        if($c_s==='current'){
            $c_s=Season::get_current();
        }

        if (!$this->id || !$c_s || !is_numeric($c_s)) {
            return [];
        }

        $query = 
        "SELECT `game_name`, `url`, `id`
        FROM `games`
        WHERE `id` IN (
            SELECT `game_id`
            FROM `subteams`
            WHERE `id` IN (
                SELECT `subteam_id`
                FROM `subteam_seasons`
                WHERE `season_id` = ?
            )
            AND `id` IN (
                SELECT `subteam_id`
                FROM `player_seasons`
                WHERE `user_id` = ? AND `season_id` = ?
            )
        )
        ORDER BY `game_name` ASC";

        $res = $this->db->query($query, $c_s, $this->id, $c_s)->fetchAll();

        return $res;
    }

    /**
     * Gets all seasons the student competed
     * @return  array
     */

    public function seasons_competed_in() {
        if (!$this->id) {
            return [];
        }
    }

    /**
     * Updates user's profile photo
     * @param   string  $url
     * @return  boolean
     */

    public function update_profile_photo($url){
        if (!$url){
            return false;
        }

        $query=
        "UPDATE `users`
        SET `pfp_url` = ?
        WHERE `user_id` = ?";

        $res = $this->db->query($query, $url, $this->id)->affectedRows();
        return $res > 0;
    }

    /**
     * Gets all profile data for a user
     * @param   int $user_id
     * @return  array
     */

    public function get_profile_data() {
        if (!$this->id) {
            return [];
        }

        $query =
        "SELECT *
        FROM `user_profile_display`
        WHERE `user_id` = ?";

        $res = $this->db->query($query, $this->id)->fetchArray();

        if (empty($res)) {
            return [];
        }

        $res['school'] = $this->get_team_name();

        if (!$res['show_grad_year']) {
            unset($res['grad_year']);
        }

        if ($res['twitch_username']) {
            $res['twitch_href'] = 'https://twitch.tv/' . $res['twitch_username'];
        } else {
            unset($res['twitch_username']);
        }

        $res['games'] = $this->games_competing_in('current');
        
        return $res;
    }

    /**
     * Gets events user is competing in (upcoming matches)
     * @return  array
     */

    public function get_events() {
        if (!$this->id) {
            return [];
        }

        $c_s = Season::get_current();

        $query = 
        "SELECT events.id, events.event_home, events.event_away, events.event_time, events.event_date, events.event_game, player_seasons.subteam_id
        FROM events
        LEFT JOIN subteams
            ON subteams.id = events.event_home OR subteams.id = events.event_away
        LEFT JOIN subteam_seasons
            ON subteam_seasons.subteam_id = subteams.id AND subteam_seasons.season_id = ?
        LEFT JOIN player_seasons
            ON player_seasons.user_id = ? AND player_seasons.season_id = ?
        WHERE player_seasons.subteam_id = subteams.id";
        $res = $this->db->query($query, $c_s, $this->id, $c_s)->fetchAll();
        return $res;
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
     * gets user's ign for game
     * @param   int $user_id
     * @param   int $game_id
     * @return  string
     */

    public static function get_ign_with_id($user_id, $game_id){
        if (!$user_id || !$game_id){
            return 'None';
        }

        $query=
        "SELECT `ign`
        FROM `user_igns`
        WHERE `user_id` = ? AND `game_id` = ?";

        $db = new tecdb();

        $res = $db->query($query, $user_id, $game_id)->fetchArray();
        return $res['ign'] ?? 'None';
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
}

?>