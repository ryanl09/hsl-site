<?php

abstract class FormatterService {
    protected $name;

    public function __construct($name){
        $this->name = $name;
    }

    public abstract function format($data);
}

?>