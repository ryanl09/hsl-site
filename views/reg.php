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
                    <h3>Teams</h3>
                </div>
                <hr class="sep">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>School</th>
                            <th>Team Manager</th>
                            <th>Email</th>
                            <th>Games</th>
                            <th>Registration Link</th>
                            <th>Slug</th>
                            <th>Player Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <pre>
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
                            AND teams.team_type = \"hs\"";

                            $res=$db->query($query)->fetchAll();

                            foreach ($res as $i => $row){
                                $reg_link = 'https://tecesports.com/register/' . $row['schoolcode'];
                                $ahref = '<a class="copy-sc" data-link="'.$reg_link.'"><i class="bx bx-copy"></i><a>';

                                echo '<tr>'
                                echo td($row['id']);
                                echo td($row['team_name']);
                                echo td($row['name']);
                                echo td($row['email']);
                                echo td($row['']);
                                echo td($row['schoolcode']);
                                echo td($ahref);
                                echo '</tr>';
                                print_r($row);
                            }
                        ?>
                        </pre>
                    </tbody>
                </table>
            </div>
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

                        ?>
                    </tbody>
                </table>
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