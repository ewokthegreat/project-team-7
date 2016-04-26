<?php

/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/16/2016
 * Time: 4:41 PM
 * Details: Abstract class used to assist in storing
 * objects into database.
 */
abstract class DBObject {
    /**
     * @return string The table name.
     */
    abstract public function getTableName();

    /**
     * @return array Fields of the table.
     */
    public function getProperties() {
        $props = array();

        foreach ($this as $key => $value) {
            $props[$key] = $value;
        }

        return $props;
    }
}