<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');
require_once($path . '/classes/util/tecdb.php');

start_content_full(1, 'settings');


if (!isset($_SESSION['user'])){
    echo '<p>Error: you must be signed in</p>';
    end_content_full(1);
    die();
}

$user = $_SESSION['user'];
$role = $user->get_role();

if ($user->get_username()==='ryan'){
    $role='player';
}

echo '<h2 style="margin-bottom:10px;">Settings</h2>';

switch ($role){
    case 'player':?>
        <div class="half">
            <div class="row e1">
                <div class="box">
                    <div class="input set">
                        <label for="name">Name:</label>
                        <input type="text" id="name" value="<?php echo $user->get_name(); ?>" disabled>
                    </div>
                    <div class="input set">
                        <label for="username">Username:</label>
                        <input type="text" id="username" value="<?php echo $user->get_username(); ?>" disabled>
                    </div>
                    <div class="input set">
                        <label for="email">Email:</label>
                        <input type="text" id="email" value="<?php echo $user->get_email(); ?>" disabled>
                    </div>
                    <div class="input set btn">
                        <label for="school">School:</label>
                        <input type="text" id="school" value="<?php echo $user->get_team_name(); ?>" disabled>
                        <button class="btn-leave">Leave</button>
                    </div>
                    <div class="input set btn">
                        <label for="apitoken">API Token:</label>
                        <input type="text" id="apitoken" value="<?php echo $user->get_api_token(); ?>" disabled>
                        <button class="copy-api-token">Copy</button>
                    </div>
                </div>
            </div>
            <div class="row e1">
                <div class="box"></div>
            </div>
        </div>
    <?php break;
    case 'team_manager':
        break;
}


?>

<?php end_content_full(1); ?>