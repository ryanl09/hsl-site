<?php

//TESTED

require_once('CreateService.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/event/Event.php');

class CreateEventService extends CreateService {
    public function __construct($tecdb) {
        parent::__construct($tecdb);
    }

    /**
     * Creates an event: home, away, date, time, game
     * @return  int|Event
     */

     /*$es = new CreateEventService();

        $params = array(
            'home' => 0,
            'away' => 1,
            'date' => '5/5/2022',
            'time' => '13:00:00',
            'game' => 0,
        );

        $event_id = $es->create($params);*/

    public function create($params) {
        if(!$params || count($params) < 5) {
            return 0;
        }

        $home = $params['home'];
        $away = $params['away'];
        $date = $params['date'];
        $time = $params['time'];
        $game = $params['game'];

        $query = 
        "INSERT INTO `events` (event_home, event_away, event_date, event_time, event_game)
        VALUES (?, ?, ?, ?, ?)";

        $event_id = $this->db->query($query, $home, $away, $time, $date, $game)->lastInsertID();
        return new Event($event_id, $this->db);
    }
}

?>