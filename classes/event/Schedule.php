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

        if ($t_count % 2 !== 0) {
            $team_ids[] = 0; // 'bye' week
        }

        for ($j = 0; $j < $d_count; $j++) {
            $days[$j]=intval(date('N', strtotime($days[$j])));
        }

        $start_day = date('m-d-Y', strtotime($start_day));

        if (!$len || !$d_count || !$t_count) {
            return [];
        }

        $s = array();

        $temp = array(
            'date' => $start_day,
            'matches' => []
        );

        $d_idx = 0;
        $start = intval(date('N', strtotime($start_day)));
        $d_arr = intval(date('N', $days[0]));
        if ($start !== $d_arr) { //find the closest day after the start date if its not first day of days list
            if ($d_arr < $start) {
                while ($d_arr < $start) {
                    $d_idx = ($d_idx + 1) % $d_count;
                }
            } else {
                $dta = 0;//days to add
                while ($start + $dta < $d_arr) {
                    $dta++;
                }
                $start_day = date('m-d-Y', strtotime($start_day . ' + ' . $dta . ' days'));
            }
        }
        $mat = 0;


        $af = array_fill(0, 6, 0);

        for ($i = 1; $i < $games; $i++) {
            $_s = ($i - 1) % ($len/2); //squash index to be between 1 - n (teams count)
            $h = $team_ids[$_s];
            $a = $team_ids[($len-1) - $_s];
            if ($i  % ($len / 2) === 0) {
                $last = $team_ids[$len-1];
                unset($team_ids[$len-1]);
                array_splice($team_ids, 1, 0, $last);
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
                }
                
                $start_day = date('m-d-Y', strtotime($start_day . ' + ' . $add_days . ' days'));

                $temp = array(
                    'date' => $start_day,
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