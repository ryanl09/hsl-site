<?php

class Schedule {
    private function __construct() { }

    /**
     * Generate the schedule (round-robin)
     * @param   string  $start_day
     * @param   array   $days
     * @param   array   $times
     * @param   int     $num_weeks
     * @param   array   $team_ids
     * @return  array
     */

    public static function generate($start_day, $days, $times, $num_weeks, $team_ids) {
        $len = count($team_ids);
        $d_count = count($days);
        $t_count = count($times);
        $games = $d_count * $t_count * $num_weeks;

        $dt = new DateTime(date('Y-m-d', strtotime($start_day)));

        if ($t_count % 2 !== 0) {
            $team_ids[] = 0; // 'bye' week
        }

        for ($j = 0; $j < $d_count; $j++) {
            $days[$j]=intval(date('N', strtotime($days[$j])));
        }

        if (!$len || !$d_count || !$t_count) {
            return [];
        }

        $s = array(
            'week' => 'Week 1'
        );
        $weeks=1;

        $d_idx = 0;
        $start = intval($dt->format('N'));
        $d_arr = $days[0];
        
        if ($start !== $d_arr) { //find the closest day after the start date if its not first day of days list
            if ($d_arr < $start) {
                while ($d_arr < $start) {
                    $d_idx = ($d_idx + 1) % $d_count;
                    $d_arr = $days[$d_idx];
                }
                $dt->modify("+" . ($d_arr - $start) . " day");
            } else {
                $dta = 0;//days to add
                while ($start + $dta < $d_arr) {
                    $dta++;
                }
                $dt->modify("+$dta day");
                //$start_day = date('m-d-Y', strtotime($start_day . ' + ' . $dta . ' days'));
            }
        } 
        $mat = 0;

        $temp = array(
            'date' => $dt->format('l') . ', ' . $dt->format('F') . ' ' . $dt->format('d') . ' ' . $dt->format('Y'),
            'matches' => []
        );

        $af = array_fill(0, 6, 0);

        for ($i = 1; $i < $games; $i++) {
            $_s = ($i - 1) % ($len/2); //squash index to be between 1 - n/2 (teams count)
            $h = $team_ids[$_s];
            $a = $team_ids[($len-1) - $_s];
            if ($i  % ($len / 2) === 0) {
                $last = $team_ids[$len-1];
                unset($team_ids[$len-1]);
                array_splice($team_ids, 1, 0, $last);
            }

            if ($h === 0 || $a === 0) {
                $games++;
                continue;
            }

            $temp['matches'][] = array(
                'home' => $h,
                'away' => $a,
                'time' => $times[$mat % $t_count]
            );
            $mat++;

            if (count($temp['matches']) === $t_count) {
                $s[] = $temp;

                $d1 = $days[$d_idx];
                $d_idx = ($d_idx + 1) % $d_count;
                //$d1 = $days[(($d_idx - 1) % $d_count)];
                $d2 = $days[$d_idx];
                $add_days = $d2-$d1;
                if ($add_days < 0) {
                    $add_days = $add_days + 7;
                    $weeks++;
                }

                $s[] = array(
                    'week' => 'Week ' . $weeks
                );
                
                $dt->modify("+$add_days day");
                //$start_day = date('+ ' . $add_days . ' days', strtotime($start_day));

                $temp = array(
                    'date' => $dt->format('l') . ', ' . $dt->format('F') . ' ' . $dt->format('d') . ' ' . $dt->format('Y'),
                    'matches' => [],
                    'd1' => $d1,
                    'd2' => $d2,
                    'add' => $start_day . ' + ' . $add_days . ' days'
                );
            }
        }
        return $s;
    }
}

?>