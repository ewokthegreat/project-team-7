<?php

/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/16/2016
 * Time: 4:41 PM
 */
abstract class DBObject {
    
    abstract public function getTableName();
    
    public function getProperties() {
        $props = array();

        foreach ($this as $key => $value) {
            $props[$key] = $value;
        }

        return $props;
    }
}