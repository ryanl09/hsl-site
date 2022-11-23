<?php

require_once('User.php');

class Admin extends User {
    public function __construct($db, $id){
        parent::__construct($db, $id, 'admin');
    }

    /**
     * ban a user
     * @param   int     $user_id
     * @param   string  $reason
     * @param   string  $expires
     * @return  boolean
     */

    public function ban_user($user_id, $reason){

    }
}

?>