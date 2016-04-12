<?php

/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/12/2016
 * Time: 3:57 AM
 */
class DictionaryData {
    private $dictionaryArray;
    private $weight;

    public function __construct($pathToCSV, $weight = 1) {
        $data = array_map('str_getcsv', file($pathToCSV));
        
        $this->setDictionaryArray($data);
        $this->setWeight($weight);
    }

    /**
     * @return mixed
     */
    public function getDictionaryArray()
    {
        return $this->dictionaryArray;
    }

    /**
     * @param mixed $dictionaryArray
     * @return DictionaryData
     */
    public function setDictionaryArray($dictionaryArray)
    {
        $this->dictionaryArray = $dictionaryArray;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getWeight()
    {
        return $this->weight;
    }


    /**
     * @param mixed $weight
     * @return DictionaryData
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
        return $this;
    }
}