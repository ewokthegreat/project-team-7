<?php
/**
 * Created by IntelliJ IDEA.
 * User: Clark
 * Date: 3/29/2016
 * Time: 9:33 PM
 */

class JsonHandler {

    protected static $_messages = array(
        JSON_ERROR_NONE => 'No error has occurred',
        JSON_ERROR_DEPTH => 'The maximum stack depth has been exceeded',
        JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
        JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
        JSON_ERROR_SYNTAX => 'Syntax error',
        JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded'
    );


    /**
     * @param $value
     * @param int $options
     * @return string
     *
     * This method is used to create JSON objects
     */
    public static function encode($value, $options = 0) {
        $result = json_encode($value, $options);

        if($result)  {
            return $result;
        }
        throw new RuntimeException(static::$_messages[json_last_error()]);
    }

    /**
     * @param $json
     * @param bool|false $assoc
     * @return mixed
     *
     * This method is used to convert JSON objects to PHP objects
     */
    public static function decode($json, $assoc = false) {
        $result = json_decode($json, $assoc);

        if($result) {
            return $result;
        }
        throw new RuntimeException(static::$_messages[json_last_error()]);
    }

}

 ?>