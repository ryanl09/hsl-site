<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');
require_once($path . '/classes/util/tecdb.php');
require_once($path . '/classes/user/User.php');

start_content_full(1, 'reg');

admin_block();

/**
 * show selection buttons if no target specified
 */

function display_buttons(){
    echo '<div class="btn-container">';
    echo '<a href="https://tecesports.com/reg/hs"><span>High School</span></a>';
    echo '<a href="https://tecesports.com/reg/ymca"><span>YMCA</span></a>';
    echo '</div>';
}

$p = $_SESSION['current_page'];
if (count($p) < 3){
    display_buttons();
} else { 
    $db=new tecdb();
    switch (strtolower($p[2])){
        case 'hs':?>
            <div class="hs-table">
                <div class="tbl-title">
                    <?php
                        $query=
                        "SELECT teams.team_name
                        FROM users
                        INNER JOIN teams
                            ON users.team_id = teams.id
                        WHERE users.user_id = ?";

                        $re = $db->query($query, $_SESSION['user']->get_id())->fetchArray();
                        $team = $re['team_name'];

                        $query=
                        "SELECT COUNT(*) as c
                        FROM `teams`
                        WHERE id NOT IN (1,2,3,24,25,26)";
                        $res = $db->query($query)->fetchArray();
                        echo '<h3>Teams (' . $res['c'] . ') | Your current team: '.$team.'</h3>';
                    ?>
                </div>
                <hr class="sep">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>School</th>
                            <th>Team Manager</th>
                            <th>Email</th>
                            <th>Registration Link</th>
                            <th>Slug</th>
                            <th>Player Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $query=
                            "SELECT teams.id, teams.team_name, users.name, users.email, teams.slug, teams.schoolcode
                            FROM `users`
                            INNER JOIN `teams`
                                ON teams.user_id = users.user_id
                            WHERE teams.user_id IN (
                                SELECT DISTINCT users.user_id
                                FROM users
                                WHERE users.role = \"team_manager\"
                            )
                            AND users.team_id NOT IN (24, 25, 26)
                            AND teams.team_type = \"hs\"";

                            $res=$db->query($query)->fetchAll();

                            $query=
                            "SELECT team_id, COUNT(*) AS player_count
                            FROM users
                            WHERE `role` = \"player\"
                                AND team_id NOT IN (24, 25, 26)
                            GROUP BY team_id";
                            $count = $db->query($query)->fetchAll();

                            $counts = array();
                            for ($i = 0; $i < count($count); $i++){
                                $counts[$count[$i]['team_id']]=$count[$i]['player_count'];
                            }

                            $opts = '';

                            $csv='';

                            foreach ($res as $i => $row){
                                $reg_link = 'https://tecesports.com/register/' . $row['schoolcode'];
                                $ahref = '<a class="copy-sc" data-link="'.$reg_link.'"><i class="bx bx-copy"></i>Copy</a>';
                                echo '<tr>';
                                echo td($row['id'], 'team-id');
                                echo td($row['team_name']);
                                echo td($row['name']);
                                echo td($row['email']);
                                echo td($ahref);
                                echo td($row['slug']);
                                echo td($counts[$row['id']] ?? 0);
                                echo '</tr>';

                                $opts .= '<option value="'.$row['id'].'">';
                                $opts .= $row['team_name'];
                                $opts .= '</option>'; 
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="tbl-title">
                <?php
                    $query=
                    "SELECT COUNT(*) as c
                    FROM `users`
                    WHERE `role` = \"player\"
                        AND team_id NOT IN (1,2,3,24,25,26)";
                    $res = $db->query($query)->fetchArray();
                    echo '<h3>Players (' . $res['c'] . ')</h3>';
                ?>
                <select name="team" class="team">
                    <option value="-1" selected>Any Team</option>
                    <?php echo $opts; ?>
                </select>
            </div>
            <hr class="sep">
            <div class="hs-players-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Team</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $query=
                            "SELECT users.user_id, users.name, users.username, users.team_id, teams.team_name
                            FROM `users`
                            INNER JOIN `teams`
                                ON users.team_id = teams.id
                            WHERE users.role = \"player\" AND users.team_id NOT IN (1, 2, 3, 24, 25, 26)
                            ORDER BY users.team_id";
                            $res = $db->query($query)->fetchAll();

                            foreach ($res as $i => $row){
                                echo '<tr class="team-'.$row['team_id'].'">';
                                echo td($row['user_id']);
                                echo td($row['name']);
                                echo td($row['username']);
                                echo td($row['team_name']);
                                echo '</tr>';
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <div>
                <pre>
                <?php

                    $db=new tecdb();
                    $sql=
                    "SELECT users.name, user_igns.ign, games.game_name, teams.team_name, subteams.division, users.team_id
                    from `users`
                    Inner JOIN `user_igns`
                        ON users.user_id = user_igns.user_id
                    INNER JOIN player_seasons
                        ON player_seasons.user_id = users.user_id
                    INNER JOIN subteams
                        ON subteams.id = player_seasons.subteam_id
                    INNER JOIN teams
                        ON teams.id=users.team_id
                    INNER JOIN games
                        ON games.id = subteams.game_id
                    where `role`=\"player\"
                    ORDER BY users.team_id
                    ";

                    $res = $db->query($sql)->fetchAll();

                    $csv = 'Name,IGN,Game,School,Team ID<br>';

                    foreach ($res as $i => $row){
                        //print_r($row);
                        $csv .= $row['name'] . ',' . $row['ign'] . ',' . $row['game_name'] . ' - Division ' . $row['division'] . ',' . $row['team_name'] . ',' . $row['team_id'] . '<br>';
                    }
                ?>
                </pre>

                <h1>csvs</h1>
                <p>ID,Name,Email,School</p>
                <?php echo $csv; ?>
            </div>
        <?php break;
        case 'ymca': ?>
            <div class="ymca-table">

            </div>
        <?php break;
        default:
            display_buttons();
            break;
    }
}    
end_content_full(1); ?>