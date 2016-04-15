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
//        print_r($flaggedPosts);
        //sort array here
        self::sortFlaggedPostArray($flaggedPosts);

        foreach($flaggedPosts as $post) {
            print_r($post);
            print_r("Score: ");
            //this is a static function call
//            print_r(self::getTotalWeightOfFlaggedPost($post));
            //this is a regular function call form the object.
            //i believe this looks more object oriented and would like to keep this
            print_r($post->getTotalWeight());
            echo '<br/>';
        }
        echo '</pre>';

        return $flaggedPosts;
    }

    /**
     * @param $flaggedPost
     * @return int This function returns the total weight of a post, by adding all the weights of all
     * the flagged words in a post.
     */
    public static function getTotalWeightOfFlaggedPost($flaggedPost) {
        $flaggedWordsList = $flaggedPost->getFlaggedWords();

        $totalWeight = 0;

        foreach($flaggedWordsList as $flaggedWord) {
            $totalWeight += $flaggedWord->getDictWeight();
        }

        return $totalWeight;
    }

    /**
     * This function takes in a flagged post array and sorts it.
     * I am using amperstand to signify I will be doing a pass by reference
     * @param $flaggedPostArray
     */
    public static function sortFlaggedPostArray(&$flaggedPostArray) {
        $arrayLength = sizeof($flaggedPostArray);

        //bubble sort algorithm
        for ($i = ($arrayLength - 1); $i >= 0; $i--) {
            for ($j = 1; $j <= $i; $j++) {
                if ($flaggedPostArray[$j-1]->getTotalWeight() > $flaggedPostArray[$j]->getTotalWeight()) {
                    $temp = $flaggedPostArray[$j-1];
                    $flaggedPostArray[$j-1] = $flaggedPostArray[$j];
                    $flaggedPostArray[$j] = $temp;
                }
            }
        }

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

    public function getTotalWeight() {
        $totalWeight = 0;

        foreach($this->getFlaggedWords() as $flaggedWord) {
            $totalWeight += $flaggedWord->getDictWeight();
        }
        return $totalWeight;
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