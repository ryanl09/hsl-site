<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/controllers/DashboardController.php');
require_once($path . '/controllers/LeagueController.php');
require_once($path . '/controllers/FeedController.php');
require_once($path . '/controllers/MyGamesController.php');
require_once($path . '/controllers/StatsController.php');
require_once($path . '/controllers/MessagesController.php');
require_once($path . '/controllers/ProfileController.php');
require_once($path . '/controllers/Error404Controller.php');

class ClientRequest {
    private $ctrl;

    public function __construct($arg) {
        if (!$arg){
            $arg='dashboard';
        }

        switch ($arg) {
            case 'dashboard':
                $this->ctrl = new DashboardController();
                break;
            case 'league':
                $this->ctrl = new LeagueController();
                break;
            case 'feed':
                $this->ctrl = new FeedController();
                break;
            case 'mygames':
                $this->ctrl = new MyGamesController();
                break;
            case 'stats':
                $this->ctrl = new StatsController();
                break;
            case 'messages':
                $this->ctrl = new MessagesController();
                break;
            case 'user':
                $this->ctrl = new ProfileController();
                break;
            default:
                $this->ctrl = new Error404Controller();
                break;
        }
    }
}

?>