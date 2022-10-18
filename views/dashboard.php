<?php


$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');
require_once($path . '/classes/general/Game.php');
require_once($path . '/classes/event/Event.php');
require_once($path . '/classes/user/User.php');
require_once($path . '/classes/util/Sessions.php');
require_once($path . '/classes/team/Team.php');

require_once('redirect_login.php');

$role='';
if (isset($_SESSION['user'])){
    $role=$_SESSION['user']->get_role();
    if ($role==='admin'&&count($_SESSION['current_page'])>2&&$_SESSION['current_page'][2]) {
        $role=$_SESSION['current_page'][2];
    }    
}

start_content_full(1, 'dashboard');

switch ($role) {
    case 'player': ?>
        <div class="row e3">
            <div class="box tall">
                <h4>Matches</h4>
            </div>
        </div>
        <?php break;
    case 'team_manager':
        include_once($path . '/views/dashboard/teammanager.php');
        break;
    case 'caster': ?>

        <?php break;
    case 'college': ?>

        <?php break;
    case 'admin': ?>

        <?php break;
    default: ?>
        <?php break;
} 

end_content_full(1); ?>