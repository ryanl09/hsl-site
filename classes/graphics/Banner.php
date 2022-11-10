<?php

class Banner {
    private function __construct() { }

    /**
     * gets banner for certain page
     * @param   string  $page_name
     * @return  string
     */

    public static function get($db, $page_name) {
        if (!$page_name){
            return '';
        }

        $query =
        "SELECT banners.url
        FROM `banners`
        INNER JOIN `page_banners`
            ON page_banners.banner_id = banners.id
        WHERE page_banners.page_name = ?";

        $res = $db->query($query, $page_name)->fetchArray();
        return $res['url'] ?? '';
    }
}

?>