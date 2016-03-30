<?php
/**
 * Created by IntelliJ IDEA.
 * User: Clark
 * Date: 3/29/2016
 * Time: 9:14 PM
 */

class WordBankHandler{

    public static function getWordBank(){
        $file = "../doc/SpiderWordBank.csv";

        if(!file_exists($file) || !is_readable($file))
            return FALSE;

        $header = NULL;
        $data = array();
        if(($handle = fopen($file, "r")) !== FALSE){
            while(($row = fgetcsv($handle,1000,",")) !== FALSE){
                if(!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }
        return $data;
    }
}



?>