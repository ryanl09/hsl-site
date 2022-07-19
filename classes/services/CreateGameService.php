<?php

class CreateGameService extends CreateService {
    public function __construct() {
        parent::__construct();
    }

    public function create($params) {
        if (!$params || empty($params)) {
            return 0;
        }

        $game_name = $params['game_name'];

        $query = 
        "INSERT INTO `games` (game_name)
        VALUES (?)";

        $id = $this->db->query($query, $game_name)->lastInsertID();
        return new Game($id);
    }
}