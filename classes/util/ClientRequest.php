<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/controllers/AdminController.php');
require_once($path . '/controllers/DashboardController.php');
require_once($path . '/controllers/Error404Controller.php');
require_once($path . '/controllers/EventPanelController.php');
require_once($path . '/controllers/EventController.php');
require_once($path . '/controllers/EventsController.php');
require_once($path . '/controllers/FeedController.php');
require_once($path . '/controllers/ForgotController.php');
require_once($path . '/controllers/GraphicsController.php');
require_once($path . '/controllers/LeagueController.php');
require_once($path . '/controllers/LoginController.php');
require_once($path . '/controllers/MessagesController.php');
require_once($path . '/controllers/PrivacyController.php');
require_once($path . '/controllers/ProfileController.php');
require_once($path . '/controllers/RegController.php');
require_once($path . '/controllers/RegisterController.php');
require_once($path . '/controllers/SettingsController.php');
require_once($path . '/controllers/StandingsController.php');
require_once($path . '/controllers/StatsController.php');
require_once($path . '/controllers/TeamController.php');
require_once($path . '/controllers/TeamsController.php');
require_once($path . '/controllers/TermsController.php');
require_once($path . '/controllers/TickerController.php');
require_once($path . '/controllers/UpdatesController.php');
require_once($path . '/controllers/HomeController.php');
require_once($path . '/controllers/ActivateController.php');

require_once($path . '/classes/services/LogoutService.php');

class ClientRequest {
    private $ctrl;

    public function __construct($arg) {
        if (!$arg){
            if (session_id() && isset($_SESSION['user']) && $_SESSION['user']->get_id()){
                $arg='dashboard';
            }else{
                $arg='home';
            }
        }

        $ex = explode('/', $arg);
        if (count($ex) > 1){
            $arg=$ex[0];
            $ex=$ex[1];
        }

        switch ($arg) {
            case 'home':
                $this->ctrl = new HomeController();
                break;
            case 'activate':
                $this->ctrl = new ActivateController($ex);
                break;
            case 'dashboard':
                $this->ctrl = new DashboardController();
                break;
            case 'series':
                $this->ctrl = new LeagueController();
                break;
            case 'feed':
                $this->ctrl = new FeedController();
                break;
            case 'stats':
                $this->ctrl = new StatsController();
                break;
            case 'messages':
                $this->ctrl = new MessagesController();
                break;
            case 'user':
                $this->ctrl = new ProfileController($ex);
                break;
            case 'login':
                $this->ctrl = new LoginController();
                break;
            case 'register':
                $this->ctrl = new RegisterController();
                break;
            case 'logout':
                LogoutService::logout();
                break;
            case 'admin':
                $this->ctrl = new AdminController();
                break;
            case 'eventpanel':
                $this->ctrl = new EventPanelController();
                break;
            case 'event':
                $this->ctrl = new EventController();
                break;
            case 'privacy':
                $this->ctrl = new PrivacyController();
                break;
            case 'terms':
                $this->ctrl = new TermsController();
                break;
            case 'ticker':
                $this->ctrl = new TickerController();
                break;
            case 'graphics':
                $this->ctrl = new GraphicsController();
                break;
            case 'forgot':
                $this->ctrl = new ForgotController();
                break;
            case 'events':
                $this->ctrl = new EventsController();
                break;
            case 'reg':
                $this->ctrl = new RegController();
                break;
            case 'team':
                $this->ctrl = new TeamController($ex);
                break;
            case 'settings':
                $this->ctrl = new SettingsController();
                break;
            case 'standings':
                $this->ctrl = new StandingsController();
                break;
            case 'teams':
                $this->ctrl = new TeamsController();
                break;
            default:
                $this->ctrl = new Error404Controller();
                break;
        }
    }
}

?>