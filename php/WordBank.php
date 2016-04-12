<?php
/**
 * Created by IntelliJ IDEA.
 * User: Clark
 * Date: 3/29/2016
 * Time: 9:14 PM
 */

class WordBankHandler{

    public static function getWordBank($passedFile = ''){
        $file = $passedFile;
        $finalArray = array();

        if(!file_exists($file) || !is_readable($file)) {
            return FALSE;
        }
        $data = array_map('str_getcsv', file($file));

        $finalArray[basename($passedFile, ".csv")] = $data[0];
        return $finalArray;
    }
}



?>
