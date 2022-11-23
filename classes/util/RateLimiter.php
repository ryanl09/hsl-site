<?php

class RateLimiter {
    private $max;
    private $seconds;

    public function __construct($requests_per, $seconds) {
        $this->max = $requests_per;
        $this->seconds = $seconds;
    }

    /**
     * public add function
     * @return  boolean
     */

    public function add(){
        $this->check_reqs();
        $add = $this->_add();
        return $add;
    }

    /**
     * add a request from the user
     * @return  boolean
     */

    private function _add(){
        if (count($_SESSION['requests']) >= $this->max){
            return false;
        }

        $_SESSION['requests'][] = new DateTime();
        return true;
    }

    /**
     * removes old requests
     */

    private function check_reqs(){
        $count = count($_SESSION['requests']);
        if (!$count){
            return;
        }

        for($i=$count-1; $i>=0; $i--){
            $cur = $_SESSION['requests'][$i];
            $now = new DateTime();
            $diff = $now->getTimestamp() - $cur->getTimestamp();

            if ($diff >= $this->seconds){
                array_splice($_SESSION['requests'], $i, 1);
            }
        }
    }

    /**
     * gets time until next request allowed
     * @return  int
     */

    public function time_until_next(){
        $count = count($_SESSION['requests']);
        if (!$count){
            return 0;
        }

        $now = new DateTime();
        $cur = $_SESSION['requests'][$count - 1];
        $diff = $now->getTimestamp() - $cur->getTimestamp();
        return $this->seconds - $diff;
    }

    public function allow() {
        array_shift($_SESSION['requests']);
        $this->_add();
    }
}

?>