<?php

class IPChecker {
    private $ip;
    private $db;

    public function construct($db) { 
        $this->ip = $this->get_ip();
        $this->db = $db;
    }

    private function get_ip() {
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function check_ip() {
        if (!$this->ip){
            return false;
        }

        $query=
        "SELECT *
        FROM `malicious_ips`
        WHERE `ip_addr` = ?";

        $res = $this->db->query($query, $this->ip)->numRows();
        return $res > 0;
    }
}

?>