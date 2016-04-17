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
    private function doAlgorithm() {
        $startTime = microtime(true);
        $flaggedPosts = array();
       // $isPlayerNames = false;

        echo "Adam Testing";

        foreach ($this->getPostDataArray() as $currentPost) {// iterate through each post
            $flaggedInstances = array(); //create flagdict word array
            foreach ($this->getDictionaryData() as $currentDict) { //iterate through each dictionary
                foreach ($currentDict->getDictionaryArray() as $dictWord) { //iterate through each word in dictionary
                    if ($currentDict->getName() === 'nflPlayers') { //if dictionary is nfl players
                        //strpos method will be used o match the dict word to the entire message as a string
                        if (strpos(strtolower($currentPost->getAllWordsAsString()), strtolower($dictWord)) !== FALSE) {
                            array_push($flaggedInstances,
                                new FlaggedInstance($currentDict->getName(),
                                    $currentDict->getWeight(),
                                    strtolower($dictWord)));
                        }
                    } else { //the dictionary is not a nfl players dictionary/
                        foreach ($currentPost->getWordArray() as $currentPostWord) { //iterate through each word in the post
                            if (strtolower($currentPostWord) === strtolower($dictWord)) { //match word by word
                                array_push($flaggedInstances,
                                    new FlaggedInstance($currentDict->getName(),
                                        $currentDict->getWeight(),
                                        strtolower($dictWord)));
                                //break; //Uncomment this if you want to avoid duplicate dictionary words per post.
                            }
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

        echo "Average weight per post: ";
        echo self::getAverageWeightPerFlaggedPosts($flaggedPosts);
        echo '<br/>';

        $flaggedWordArray = self::getFlaggedWordsAndFrequency($flaggedPosts);
        print_r(self::getSortedFlaggedWordsArray($flaggedWordArray));

        self::sortFlaggedPostArray($flaggedPosts);
        foreach ($flaggedPosts as $post) {
            print_r("Score: ");
            //this is a regular function call form the object.
            print_r($post->getTotalWeight());
            echo '<br/>';
            print_r($post);
            echo '<br/>';
        }
        echo '</pre>';

        echo 'Elapsed Time: ' . (microtime(true) - $startTime) . "seconds";
        return $flaggedPosts;
    }

    /**
     * @param $flaggedPost
     * @return int This function returns the total weight of a post, by adding all the weights of all
     * the flagged words in a post.
     */
    public static function getTotalWeightOfFlaggedPost($flaggedPost)
    {
        $flaggedWordsList = $flaggedPost->getFlaggedWords();

        $totalWeight = 0;

        foreach ($flaggedWordsList as $flaggedWord) {
            $totalWeight += $flaggedWord->getDictWeight();
        }

        return $totalWeight;
    }

    /**
     * @param $flaggedPostArray
     * @return array
     */
    public static function getFlaggedWordsAndFrequency($flaggedPostArray)
    {
        $flaggedWordsArray = array();
        //sort through all flagd posts array
        foreach ($flaggedPostArray as $flaggedPost) {
            foreach ($flaggedPost->getFlaggedWords() as $flaggedWordObject) {
                $flaggedWord = $flaggedWordObject->getFlaggedWord();
                //Sees if the word already exists in the flagged word array
                if (isset($flaggedWordsArray[$flaggedWord])) {
                    //if it exists, increment the word array
                    $flaggedWordsArray[$flaggedWord]++;
                    //if it doesn't exists, then add it to the word array
                } else {
                    $flaggedWordsArray[$flaggedWord] = 1;
                }
            }
        }

        return $flaggedWordsArray;
    }

    public static function getSortedFlaggedWordsArray($flaggedWordArray)
    {
        arsort($flaggedWordArray);
        return $flaggedWordArray;
    }

    /**
     * This function takes in a flagged post array and sorts it. This uses the bubble sort algoirthm.
     * I am using amperstand to signify I will be doing a pass by reference
     * @param $flaggedPostArray
     */
    public static function sortFlaggedPostArray(&$flaggedPostArray)
    {
        $arrayLength = sizeof($flaggedPostArray);

        //bubble sort algorithm
        for ($i = ($arrayLength - 1); $i >= 0; $i--) {
            for ($j = 1; $j <= $i; $j++) {
                if ($flaggedPostArray[$j - 1]->getTotalWeight() < $flaggedPostArray[$j]->getTotalWeight()) {
                    $temp = $flaggedPostArray[$j - 1];
                    $flaggedPostArray[$j - 1] = $flaggedPostArray[$j];
                    $flaggedPostArray[$j] = $temp;
                }
            }
        }

    }

    public static function getAverageWeightPerFlaggedPosts($flaggedPostArray) {
        $sum = 0.0;
        $counter = 0;

        foreach ($flaggedPostArray as $flaggedPost) { //iterate through each post
            $sum += $flaggedPost->getTotalWeight();
            $counter++;
//            $flaggedWordArray = $flaggedPost->getFlaggedWords(); //get flagged words
//            foreach ($flaggedWordArray as $flaggedWord) { //itereate through each flagged word
//                $sum += $flaggedWord->getDictWeight();
//
//            }
        }
        return ($sum / $counter);
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

    public function getTotalWeight()
    {
        $totalWeight = 0;

        foreach ($this->getFlaggedWords() as $flaggedWord) {
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