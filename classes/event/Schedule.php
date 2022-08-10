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

        $_START = $start_day;

        $start_day = strtotime($start_day);

        for ($j = 0; $j < $d_count; $j++) {
            $days[$j]=date('N', strtotime($days[$j]));
        }


        if (!$len || !$d_count || !$t_count) {
            return [];
        }

        $s = array();

        $temp = array(
            'date' => $start_day,
            'matches' => []
        );

        $d_idx = 0;

        $start = intval(date('N', $start_day));
        $d_arr = intval(date('N', $days[0]));

        /*
                $day = intval(date('N', $start_day));
                
                $left = abs(1 - $day);
                $right = ($day-1) % 6;
                $choice = min($left, $right);
        */

        if ($start !== $d_arr) { //find the closest day after the start date if its not first day of days list
            if ($d_arr < $start) {
                while ($d_arr < $start) {
                    $d_idx = ($d_idx) + 1 % $d_count;
                }
            } else {
                $dta = 0;//days to add
                while ($start + $dta < $d_arr) {
                    $dta++;
                }
                $start_day = date('d-m-Y', strtotime($start_day . ' + ' . $dta . ' days'));
            }
        }
        $mat = 0;


        $af = array_fill(0, 6, 0);

        for ($i = 1; $i < $games; $i++) {
            $_s = ($i - 1) % ($len/2); //squash index to be between 1 - n (teams count)
            $h = $team_ids[$_s];
            $a = $team_ids[($len-1) - $_s];
            //echo "i:" . $i . ', ' . $len . ', ' . ($i % $len) . PHP_EOL;
            if ($i  % ($len / 2) === 0) {
                $last = $team_ids[$len-1];
                unset($team_ids[$len-1]);
                array_splice($team_ids, 1, 0, $last);
                //print_r($team_ids);
            }

            
            $key = $h . 'v' . $a;
            $key2 = $a . 'v' . $h;
            if ($ar[$key2] || (!$ar[$key2] && $ar[$key2]===0)){
                $key=$key2;
            }
            $ar[$key] = intval($ar[$key]) + 1;

            $af[$h-1]++;
            $af[$a-1]++;

            $temp['matches'][] = array(
                'home' => $h,
                'away' => $a,
                'time' => $times[$mat % $t_count]
            );
            $mat++;

            if (count($temp['matches']) === $t_count) {
                $s[] = $temp;
                
                $start_day = date('d-m-Y', strtotime($start_day . '+ 1 days'));

                $temp = array(
                    'date' => $start_day,
                    'matches' => []
                );
            }
        }

        ksort($ar);

        echo 'matchups:' . PHP_EOL;
        print_r($ar);

        echo 'occurences:' . PHP_EOL;
        print_r($af);

        return $s;
    }
}

?>