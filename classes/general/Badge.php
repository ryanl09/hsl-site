<?php

class Badge{
    private function __construct(){}

    /**
     * gets all user's badges
     * @param   int $user_id
     * @return  array
     */

    public static function get_user($db, $user_id){
        if (!$user_id) {
            return [];
        }

        $query = 
        "SELECT badge_types.url, badge_types.description
        FROM `badges`
        INNER JOIN `badge_types`
            ON badge_types.id = badges.badge_id
        WHERE `user_id` = ?";

        $res = $db->query($query, $user_id)->fetchAll();

        return $res;
    }

    /**
     * gets all team's badges
     * @param   int $team_id
     * @return  array
     */

    public static function get_team($db, $team_id){
        if(!$team_id){
            return [];
        }

        $query=
        "SELECT badge_types.url, badge_types.description
        FROM `badge_types`
        INNER JOIN `team_badges`
            ON badge_types.id = team_badges.badge_id
        WHERE team_badges.team_id = ?";

        $res = $db->query($query, $team_id)->fetchAll();

        return $res;
    }
}

?>