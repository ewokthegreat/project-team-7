<?php

/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/12/2016
 * Time: 5:04 AM
 */

include_once 'Report.php';

class ReportGenerator implements JsonSerializable {
    private $dictionaryData;
    private $postDataArray;
    private $flaggedPostArray;
    private $userId;
    private $pathToData;

    /**
     * ReportGenerator constructor.
     * @param $dictionaryData
     * @param $postData
     */
    public function __construct($dictionaryData, $postData, $userId, $pathToData)
    {
        $this->setDictionaryData($dictionaryData);
        $this->setPostDataArray($postData);
        $this->setUserId($userId);
        $this->setPathToData($pathToData);
        //$this->getFirstPostDate();
        //$this->getLastPostDate();
        //$this->sortPostDataArrayByDate();

        $this->populateFlaggedPostArray();
        //$this->runTestOutput();

        $this->getReportObject();
    }

    public function getReportObject() {
        $pathToDataArray = explode('/', $this->pathToData);
        $size = sizeof($pathToDataArray);
        $timeStamp = $pathToDataArray[$size - 1];

        $cleanTimeStamp = explode('__', $timeStamp);
        $finalTimeStamp = $cleanTimeStamp[0];

        self::sortFlaggedPostArray($this->flaggedPostArray);

        $flaggedWordArray = self::getFlaggedWordsAndFrequency($this->flaggedPostArray);

        $nflTeamDict = null;
        foreach ($this->getDictionaryData() as $currentDict) { //iterate through each dictionar
            if ($currentDict->getName() === 'nflTeams') { //if dictionary is nfl players
                $nflTeamDict = $currentDict;
                break;
            }
        }

        $report = new Report($this->userId,
            $finalTimeStamp,
            $this->pathToData,
            $this->getFirstPostDate(),
            $this->getLastPostDate(),
            $this->getPercentageOfFlaggedPosts(),
            $this->getAverageWeightPerFlaggedPosts($this->flaggedPostArray),
            $this->getFavoriteTeam($flaggedWordArray, $nflTeamDict ),
            $this->flaggedPostArray,
            self::getSortedFlaggedWordsArray($flaggedWordArray));

//        print_r($report);
        return $report;
    }

    public function getLastPostDate() {
        $firstDate = $this->postDataArray[0]->getDate();

        print_r($firstDate);
        return $firstDate;
    }

    public function getFirstPostDate() {
        $sizeOfPosts = sizeof($this->postDataArray);
        $lastPost = $this->postDataArray[$sizeOfPosts - 1]->getDate();

        print_r($lastPost);
        return $lastPost;
    }


    /**
     * @return mixed
     */
    public function getFlaggedPostArray()
    {
        return $this->flaggedPostArray;
    }

    /**
     * @param mixed $flaggedPostArray
     * @return ReportGenerator
     */
    public function setFlaggedPostArray($flaggedPostArray)
    {
        $this->flaggedPostArray = $flaggedPostArray;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     * @return ReportGenerator
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPathToData()
    {
        return $this->pathToData;
    }

    /**
     * @param mixed $pathToData
     * @return ReportGenerator
     */
    public function setPathToData($pathToData)
    {
        $this->pathToData = $pathToData;
        return $this;
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

    public function jsonSerialize() {
        $props = array();
        foreach($this as $key => $value) {
            $props[$key] = $value;
        }

        return $props;
    }

    /**
     * @return array of flagged posts
     */
    private function populateFlaggedPostArray() {
        $startTime = microtime(true);
        $flaggedPosts = array();
       // $isPlayerNames = false;

        foreach ($this->getPostDataArray() as $currentPost) {// iterate through each post
            $flaggedInstances = array(); //create flagdict word array
            foreach ($this->getDictionaryData() as $currentDict) { //iterate through each dictionary
                foreach ($currentDict->getDictionaryArray() as $dictWord) { //iterate through each word in dictionary
                    if ($currentDict->getName() === 'nflPlayers') { //if dictionary is nfl players
                        //strpos method will be used o match the dict word to the entire message as a string
                        if (strpos(strtolower($currentPost->getAllWordsAsString()), strtolower($dictWord)) !== FALSE) {
                            array_push($flaggedInstances,
                                new FlaggedWord($currentDict->getName(),
                                    $currentDict->getWeight(),
                                    strtolower($dictWord)));
                        }
                    } else { //the dictionary is not a nfl players dictionary/
                        foreach ($currentPost->getWordArray() as $currentPostWord) { //iterate through each word in the post
                            if (strtolower($currentPostWord) === strtolower($dictWord)) { //match word by word
                                array_push($flaggedInstances,
                                    new FlaggedWord($currentDict->getName(),
                                        $currentDict->getWeight(),
                                        strtolower($dictWord)));
                                //break; //Uncomment this if you want to avoid duplicate dictionary words per post.
                            }
                        }
                    }
                }
            }
            if (!empty($flaggedInstances)) { //if the algo picks up on any posts that are flagged as football, push to the array
                array_push($flaggedPosts,
                    new FlaggedPost($currentPost, $flaggedInstances));
            }
        }

        echo 'Elapsed Time: ' . (microtime(true) - $startTime) . "seconds";
        $this->flaggedPostArray = $flaggedPosts;
        return $flaggedPosts;
    }

    public function runTestOutput() {
        echo '<pre>';
        echo "Percentage of flagged posts: ";
        $flaggedPostPercentage = $this->getPercentageOfFlaggedPosts();
        echo $flaggedPostPercentage;
        echo '<br/>';


        echo '<pre>';
        echo "Average weight per post: ";
        echo self::getAverageWeightPerFlaggedPosts($this->flaggedPostArray);
        echo '<br/>';
        $flaggedWordArray = self::getFlaggedWordsAndFrequency($this->flaggedPostArray);

        //get nflTeam dict
        $nflTeamDict = null;
        foreach ($this->getDictionaryData() as $currentDict) { //iterate through each dictionaryecho "Favorite Team: ";
            if ($currentDict->getName() === 'nflTeams') { //if dictionary is nfl players
                $nflTeamDict = $currentDict;
                break;
            }
        }

        echo self::getFavoriteTeam($flaggedWordArray, $nflTeamDict);
        echo '<br/>';

        //$flaggedWordArray = self::getFlaggedWordsAndFrequency($flaggedPosts);
        print_r(self::getSortedFlaggedWordsArray($flaggedWordArray));
        self::sortFlaggedPostArray($this->flaggedPostArray);
        foreach ($this->flaggedPostArray as $post) {
            print_r("Score: ");
            //this is a regular function call form the object. yep.
            print_r($post->getTotalWeight());
            echo '<br/>';
            print_r($post);
            echo '<br/>';
        }
        echo '</pre>';
    }

    public function getPercentageOfFlaggedPosts() {
        $allPostsTotal = sizeof($this->postDataArray);
        $flaggedPostTotal = sizeof($this->flaggedPostArray);

        return ($flaggedPostTotal / (float) $allPostsTotal);
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
    public static function sortFlaggedPostArray(&$flaggedPostArray) {
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
        $numberOfFlaggedPosts = sizeof($flaggedPostArray);

        foreach ($flaggedPostArray as $flaggedPost) { //iterate through each post
            $sum += $flaggedPost->getTotalWeight();
        }
        return ($sum / $numberOfFlaggedPosts);
    }

    public static function getFavoriteTeam($flaggedWordArrayWithFrequency, $nflTeamDictioanry) {
        arsort($flaggedWordArrayWithFrequency); //sort flagged words first

        //create array containing all keys
        $keyArray = array_keys($flaggedWordArrayWithFrequency);

        foreach ($keyArray as $key) { //iterate through every key in word array
            foreach ($nflTeamDictioanry->getDictionaryArray() as $dictWord) { //cycle through each word in the dictionary
                if (strtolower($key) === strtolower($dictWord)) {
                    return $dictWord;
                }
            }
        }
        return "No favorite team";
    }
}

class FlaggedPost {
    private $postData; //an instance of post object
    private $flaggedWords = array(); //a list of flagged words and associated information per post

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

class FlaggedWord {
    private $dataDictName; //corresponding dict name
    private $dictWeight; //corresponding weight per word
    private $flaggedWord; //actual flagged word string

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