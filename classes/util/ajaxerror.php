<?php

class AjaxError {
    private function __construct() { }

    public static function e(...$data) {
        if (func_num_args()%2!==0){
            return json_encode(
                array(
                    'error' => 'Invalid argument list'
                )
            );
        }
        $c = 0;
        $key ='';
        $arr = array();
        foreach ($data as $d) {
            $c++;
            if ($c%2===0){
                $c=0;
                $arr[$key]=$d;
                continue;
            }
            $key = $d;
        }
        return json_encode($arr);
    }
}

?>