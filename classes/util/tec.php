<?php

class tec {
    static function safe($input) {
        return trim(htmlspecialchars($input));
    }
}

?>