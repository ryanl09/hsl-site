<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/classes/util/tecdb.php');

class TempUser{
    private function __construct() { }

    /**
     * creates a new temporary player, so we can record stats
     * @param   string  $ign
     * @param   int     $team_id
     * @return  int
     */

    public static function create($ign, $team_id) {
        if (!$ign){
            return 0;
        }
        
        $pronouns = "N/A";

        $db=new tecdb();

        $query =
        "INSERT INTO `users` (`name`, `email`, `pronouns`, `username`, `password`, `activation_key`, `activated`, `role`, `team_id`, `discord`, `request_key`, `is_temp`)
        VALUES (\"TEC Player\", \"temp@tecesports.com\", ?, ?, \"nopass\", \"noactivation\", 0, \"player\", ?, \"nodiscord\", \"norequest\", 1)";

        $user_id = $db->query($query, $pronouns, $ign, $team_id)->lastInsertID();

        return $user_id;
    }
}

?>