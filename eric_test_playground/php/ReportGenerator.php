<?php

/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/12/2016
 * Time: 5:04 AM
 */
class ReportGenerator
{
    private $dictionaryData;
    private $postDataArray;

    /**
     * ReportGenerator constructor.
     * @param $dictionaryData
     * @param $postData
     */
    public function __construct($dictionaryData, $postData)
    {
        $this->setDictionaryData($dictionaryData);
        $this->setPostDataArray($postData);
        $this->doAlgorithm();
    }

    /**
     * @param $data
     */
    private function setPostDataArray($data)
    {
        $postDataArray = array();

        foreach ($data as $post) {
            array_push($postDataArray, new PostData($post));
        }

        $this->postDataArray = $postDataArray;
    }

    /**
     * @return mixed
     */
    public function getPostDataArray()
    {
        return $this->postDataArray;
    }

    /**
     * @return mixed
     */
    public function getDictionaryData()
    {
        return $this->dictionaryData;
    }

    /**
     * @param mixed $dictionaryData
     * @return ReportGenerator
     */
    public function setDictionaryData($dictionaryData)
    {
        $this->dictionaryData = $dictionaryData;
        return $this;
    }

    /**
     * @return array of flagged posts
     */
    private function doAlgorithm()
    {
        echo '<hr/>Do whatever you have to do to do here!<hr/>';
        $flaggedPosts = array();
        
        foreach ($this->getPostDataArray() as $currentPost) {
            $flaggedInstances = array();
            foreach ($currentPost->getWordArray() as $currentPostWord) {
                foreach ($this->getDictionaryData() as $currentDict) {
                    foreach ($currentDict->getDictionaryArray() as $dictWord) {

                        if (strtolower($currentPostWord) === strtolower($dictWord)) {
                            array_push($flaggedInstances,
                                new FlaggedInstance($currentDict->getName(),
                                    $currentDict->getWeight(),
                                    strtolower($dictWord)));

                        }
                    }
                }
            }
            if (!empty($flaggedInstances)) {
                array_push($flaggedPosts,
                    new AlgoResult($currentPost, $flaggedInstances));
            }
        }

        echo '<pre>';
        print_r($flaggedPosts);
        echo '</pre>';

        return $flaggedPosts;
    }
}

class AlgoResult
{
    private $postData;
    private $flaggedWords = array();

    public function __construct($postData, $flaggedWords)
    {
        $this->setPostData($postData);
        $this->setFlaggedWords($flaggedWords);
    }

    public function getPostData()
    {
        return $this->postData;
    }

    public function setPostData($postData)
    {
        $this->postData = $postData;
    }

    public function getFlaggedWords()
    {
        return $this->flaggedWords;
    }

    public function setFlaggedWords($flaggedWords)
    {
        $this->flaggedWords = $flaggedWords;
    }
}

class FlaggedInstance
{
    private $dataDictName;
    private $dictWeight;
    private $flaggedWord;

    public function __construct($dataDictName, $dictWeight, $flaggedWord)
    {
        $this->setDataDictName($dataDictName);
        $this->setDictWeight($dictWeight);
        $this->setFlaggedWord($flaggedWord);
    }

    public function getDataDictName()
    {
        return $this->dataDictName;
    }

    public function setDataDictName($dataDictName)
    {
        $this->dataDictName = $dataDictName;
    }

    public function getDictWeight()
    {
        return $this->dictWeight;
    }

    public function setDictWeight($dictWeight)
    {
        $this->dictWeight = $dictWeight;
    }

    public function getFlaggedWord()
    {
        return $this->flaggedWord;
    }

    public function setFlaggedWord($flaggedWord)
    {
        $this->flaggedWord = $flaggedWord;
    }
}