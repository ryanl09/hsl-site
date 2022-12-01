<?php

$path = $_SERVER['DOCUMENT_ROOT'];

require_once('Stats.php');
require_once('Game.php');
require_once('Season.php');
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
        $st = new Stats($db);
        $t_s = $st->team_stats($game_id, $div);

        $cs = Season::get_current($db);

        $ids = array_map(
            function($n) {
                return $n['st_id'];
            }, $t_s
        );

        foreach ($teams as $i => $row){
            $team_id = $row['subteam_id'];
            $query=
            "SELECT COUNT(*) as wins
            FROM events
            WHERE (event_home = ? AND event_winner = event_home) OR (event_away = ? AND event_winner = event_away)
                AND `event_season` = ?";
        
            $wins = $db->query($query, $team_id, $team_id, $cs)->fetchArray();
        
            $query=
            "SELECT COUNT(*) as losses
            FROM events
            WHERE ((event_home = ? AND event_winner <> event_home) OR (event_away = ? AND event_winner <> event_away)) AND event_winner <> 0
                AND `event_season` = ?";
        
            $losses = $db->query($query, $team_id, $team_id, $cs)->fetchArray();

            $query=
            "SELECT
            SUM(CASE 
                WHEN `event_winner` = ? THEN ABS(`event_home_score` - `event_away_score`)
                WHEN `event_winner` <> ? THEN -1 * ABS(`event_home_score` - `event_away_score`)
            END) as rd
            FROM `events`
            WHERE `event_season` = ?
                AND `event_home` = ? OR `event_away` = ?";
            $r_diff = $db->query($query, $team_id, $team_id, $cs, $team_id, $team_id)->fetchArray();
        
            $query =
            "SELECT teams.team_name
            FROM subteams
            INNER JOIN teams
                ON teams.id = subteams.team_id
            WHERE subteams.id = ?";
        
            $res = $db->query($query, $team_id)->fetchArray();
            $name = $res['team_name'];

            $s1 = 0;
            $idx = array_search($team_id, $ids);
            if ($idx!==false){
                $s1 = $t_s[$idx]['stats'][0]['stat_total'];
            }
        
            $recs[] = array(
                'name' => $name,
                'st_id' => $team_id,
                'wins' => $wins['wins'],
                'losses' => $losses['losses'],
                'rounds' => $r_diff['rd'],
                's1' => $s1
            );
        }
        
        function cmp_wins($a, $b){
            return $a['wins'] < $b['wins'];
        }
        
        function cmp_losses($a, $b){
            return $a['losses'] > $b['losses'];
        }

        function cmp_rounds($a, $b){
            return $a['rounds'] < $b['rounds'];
        }

        function cmp_first_stat($a, $b){
            return $a['s1'] < $b['s1'];
        }
        
        if (!empty($recs)){
            usort($recs, "cmp_first_stat");
            usort($recs, "cmp_rounds");
            usort($recs, "cmp_losses");
            usort($recs, "cmp_wins");
        }

        return array(
            'recs' => $recs,
            'cols' => $st->get_cols_game($game_id),
            'stats' => $t_s
        );
    }
}

?>