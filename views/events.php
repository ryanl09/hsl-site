<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');
require_once($path . '/classes/event/Event.php');
require_once($path . '/classes/general/Game.php');

start_content_full(1, 'events');

echo '<script src="https://embed.twitch.tv/embed/v1.js"></script>';
    
// latest matchup
//weekly matchup
//breakdown by games
//sort search


$games = Game::get_all($db);

$game_icons='<div class="game-icons e-today">';
$game_icons2='<div class="game-icons e-all">';
foreach ($games as $i => $row){
    $sel='';
    if (!$i){
        $sel=' selected';
    }
    $game_icons .= '<img class="game-icon'.$sel.'" game-id="'.$row['id'].'" src="'.$row['url'].'" width="35" height="35">';
    $game_icons2 .= '<img class="game-icon'.$sel.'" game-id="'.$row['id'].'" src="'.$row['url'].'" width="35" height="35">';
}
$game_icons.='</div>';
$game_icons2.='</div>';

class Calendar {

    private $active_year, $active_month, $active_day;
    private $events = [];

    public function __construct($date = null) {
        $this->active_year = $date != null ? date('Y', strtotime($date)) : date('Y');
        $this->active_month = $date != null ? date('m', strtotime($date)) : date('m');
        $this->active_day = $date != null ? date('d', strtotime($date)) : date('d');
        add_all_events_for_month();
    }

    public function add_all_events_for_month() {
        
    }

    public function add_event($txt, $date, $days = 1, $color = '') {
        $color = $color ? ' ' . $color : $color;
        $this->events[] = [$txt, $date, $days, $color];
    }

    public function show() {
        $num_days = date('t', strtotime($this->active_day . '-' . $this->active_month . '-' . $this->active_year));
        $num_days_last_month = date('j', strtotime('last day of previous month', strtotime($this->active_day . '-' . $this->active_month . '-' . $this->active_year)));
        $days = [0 => 'Sun', 1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat'];
        $first_day_of_week = array_search(date('D', strtotime($this->active_year . '-' . $this->active_month . '-1')), $days);
        $html = '<div class="calendar">';
        $html .= '<div class="header">';
        $html .= '<div class="month-year">';
        $html .= date('F Y', strtotime($this->active_year . '-' . $this->active_month . '-' . $this->active_day));
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="days">';
        foreach ($days as $day) {
            $html .= '
                <div class="day_name">
                    ' . $day . '
                </div>
            ';
        }
        for ($i = $first_day_of_week; $i > 0; $i--) {
            $c = '';
            $html .= '
                <div class="day_num ignore'.$c.'">
                    ' . ($num_days_last_month-$i+1) . '
                </div>
            ';
        }
        for ($i = 1; $i <= $num_days; $i++) {
            $selected = '';
            if ($i == $this->active_day) {
                $selected = ' selected';
            }
            $html .= '<div class="day_num' . $selected . '">';
            $html .= '<span>' . $i . '</span>';
            foreach ($this->events as $event) {
                for ($d = 0; $d <= ($event[2]-1); $d++) {
                    if (date('y-m-d', strtotime($this->active_year . '-' . $this->active_month . '-' . $i . ' -' . $d . ' day')) == date('y-m-d', strtotime($event[1]))) {
                        $html .= '<div class="event' . $event[3] . '">';
                        $html .= $event[0];
                        $html .= '</div>';
                    }
                }
            }
            $html .= '</div>';
        }
        $chris  = (42-$num_days-max($first_day_of_week, 0));
        $can=true;
        for ($i = 1; $i <= $chris; $i++) {
            $c='';
            if ($chris-$i < 7&&$can){
                $c=' b-left';
                $can=false; 
            }
            $html .= '
                <div class="day_num ignore'.$c.'">
                    ' . $i . '
                </div>
            ';
        }
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }
}
?>

<div class="top-section">
    <h2>Events</h2>
    <div class="display-opts">
        <button class="default-view selected"><i class='bx bx-checkbox'></i></button>
        <button class="calendar-view"><i class='bx bxs-calendar' ></i></button>
    </div>
</div>

<div class="mid-section">
    <div class="row e2thirds">
        <div class="box events-today">
            <div class="today-top">
                <h3>Today</h3>
                <?php echo $game_icons; ?>
            </div>
            <hr class="sep">
            <p class="today-nogames">No games today!</p>
            <div class="table-today">
                <table cellspacing="0">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Home</th>
                            <th>Away</th>
                            <th>Division</th>
                            <th>Result</th>
                            <th>Stream</th>
                        </tr>
                    </thead>
                    <tbody class="table-today-tbody">
                    </tbody>
                </table>
            </div>
        </div>
        <div class="box current-event" id="current-ev" style="padding:0px !important">
            <!--
            <div class="ce-header">
                <h2>Happening now</h2>
            </div>
            <div class="matchup">
                <img src="https://tecesports.com/images/tec-black.png" width="80" height="80" alt="">
                <h2>VS</h2>
                <img src="https://tecesports.com/images/tec-black.png" width="80" height="80" alt="">
            </div>
            <button class="watch-now">Watch live!</button>-->
        </div>
        <script type="text/javascript">
        var embed = new Twitch.Embed("current-ev", {
        channel: "theesportcompany",
        parent: ["tecesports.com"]
        });

        embed.addEventListener(Twitch.Embed.VIDEO_READY, function() {
            $('.ce-te__chat').remove();
        });
        </script>
    </div>
    <div class="row e1">
        <div class="box all-events">
            <div class="all-header">
                <div class="today-top">
                    <h3>All events</h3>
                    <?php echo $game_icons2; ?>
                </div>
                <div class="sort-by">
                    <span>Sort By:</span>
                    <select name="sort-team" id="sort-team">
                        <option value="-1" selected>Any Team</option>
                    </select>
                    <select name="sort-div" id="sort-div">
                        <option value="1">D1</option>
                        <option value="2">D2</option>
                    </select>
                    <select name="sort-season" id="sort-season">
                        <option value="-1" selected>Current Season</option>
                    </select>
                    <select name="sort-time" id="sort-time">
                        <option value="all" selected>All</option>
                        <option value="upcoming">Upcoming</option>
                        <option value="past">Past</option>
                    </select>
                </div>
            </div>
            <hr class="sep">
            <div class="all-table">
                <table cellspacing="0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Home</th>
                            <th>Away</th>
                            <th>Division</th>
                            <th>Result</th>
                            <th>Stream</th>
                        </tr>
                    </thead>
                    <tbody class="table-all-tbody">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="mid-section2" style="display: none">
    <?php $calendar = new Calendar(); ?>
    <body>
        <div class="content">
            <?php echo $calendar->show();?>
        </div>
    </body>
</div>


<?php end_content_full(1); ?>