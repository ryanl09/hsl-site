<?php

require_once('User.php');

class Admin extends User {
    public function __construct($id){
        parent::__construct($id, 'admin');
    }
}

?>