<?php

require_once('User.php');

class Admin extends User {
    public function __construct($db, $id){
        parent::__construct($db, $id, 'admin');
    }
}

?>