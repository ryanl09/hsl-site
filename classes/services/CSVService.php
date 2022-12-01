<?php

require_once('FormatterService.php');

class CSVService extends FormatterService {
    public function __construct(){
        parent::__construct('csv');
    }

    /**
     * format data from db to csv 
     * @param   array   $data
     */

    public function format($data) {
        if (empty($data)){
            return '';
        }

        $out = '';

        $keys = array_keys($data[0]);
        $str = implode(',', $keys);
        $out .= $str;

        foreach ($data as $i => $row){
            $vals = array_values($row);
            $vals = implode(',', $vals);
            $out .= "\n" . $vals;
        }

        return $out;
    }
}

?>