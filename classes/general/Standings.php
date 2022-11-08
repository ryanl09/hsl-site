<?php

$path = $_SERVER['DOCUMENT_ROOT'];

require_once($path . '/classes/general/Game.php');
require_once($path . '/classes/team/SubTeam.php');

class Standings {
    private function __construct() { }

    /**
     * get standings for a certain game
     * @param   int $game_id
     * @param   int $div
     * @return  array
     */

    public static function get($db, $game_id, $div){
        $teams = Game::get_teams($db, $game_id, $div);
        foreach ($teams as $i => $row){
            $team_id = $row['subteam_id'];
            $query=
            "SELECT COUNT(*) as wins
            FROM events
            WHERE (event_home = ? AND event_winner = event_home) OR (event_away = ? AND event_winner = event_away)";
        
            $wins = $db->query($query, $team_id, $team_id)->fetchArray();
        
            $query=
            "SELECT COUNT(*) as losses
            FROM events
            WHERE ((event_home = ? AND event_winner <> event_home) OR (event_away = ? AND event_winner <> event_away)) AND event_winner <> 0";
        
            $losses = $db->query($query, $team_id, $team_id)->fetchArray();
        
            $query =
            "SELECT teams.team_name
            FROM subteams
            INNER JOIN teams
                ON teams.id = subteams.team_id
            WHERE subteams.id = ?";
        
            $res = $db->query($query, $team_id)->fetchArray();
            $name = $res['team_name'];
        
            $recs[] = array(
                'name' => $name,
                'wins' => $wins['wins'],
                'losses' => $losses['losses']
            );
        }
        
        function cmp($a, $b){
            return $a['wins'] < $b['wins'];
        }
        
        function cm2($a, $b){
            return $a['losses'] > $b['losses'];
        }
        
        usort($recs, "cm2");
        usort($recs, "cmp");

        return $recs;
    }
}

?>