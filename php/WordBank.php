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

        if(!file_exists($file) || !is_readable($file)) {
            print_r('Ain\'t no file here');
            return FALSE;
        }
        $data = array_map('str_getcsv', file($file));

        return $data[0];
    }
}



?>
