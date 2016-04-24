<?php

/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/12/2016
 * Time: 5:04 AM
 */

include_once 'Report.php';

class ReportGenerator implements JsonSerializable
{
    private $dictionaryData;
    private $postDataArray;
    private $flaggedPostArray;
    private $userId;
    private $pathToData;

    public function __construct()
    {
    }

    private function fetchRawPostData()
    {
        $path = $this->getPathToData();
        $postDataJSON = file_get_contents($path);
        $rawPostData = json_decode($postDataJSON);
        if (!is_object($rawPostData[0])) {
            $rawPostData = $rawPostData[0];
        }
        $this->setPostDataArray($rawPostData);
    }

    public function init()
    {
        $this->fetchRawPostData();
        $this->populateFlaggedPostArray();
        $this->getIntervalFlaggedPosts();

        return $this->getReportObject();
    }

    private function getReportObject()
    {
        $pathToDataArray = explode('/', $this->pathToData);
        $size = sizeof($pathToDataArray);
        $timeStamp = $pathToDataArray[$size - 1];

        $cleanTimeStamp = explode('__', $timeStamp);
        $finalTimeStamp = $cleanTimeStamp[0];

        self::sortFlaggedPostArray($this->flaggedPostArray);

        $flaggedWordArray = self::getFlaggedWordsAndFrequency($this->flaggedPostArray);

        $nflTeamDict = null;
        foreach ($this->getDictionaryData() as $currentDict) { //iterate through each dictionary
            if ($currentDict->getName() === 'nflTeams') { //if dictionary is nfl players
                $nflTeamDict = $currentDict;
                break;
            }
        }

        $test = $this->flaggedPostArray;

        if (!isset($test) || empty($test)) {

            $report = new Report(
                $this->userId,
                $this->pathToData,
                $finalTimeStamp,
                0,
                0,
                "No Team",
                date("01-01-1999"),
                date("01-01-1999"),
                0,
                array("Empty"),
                array("Empty"),
                array("Empty"));

            return $report;
        } else {
            $report = new Report(
                $this->userId,
                $this->pathToData,
                $finalTimeStamp,
                $this->getPercentageOfFlaggedPosts(),
                $this->getAverageWeightPerFlaggedPosts($this->flaggedPostArray),
                $this->getFavoriteTeam($flaggedWordArray, $nflTeamDict),
                $this->getFirstFlaggedPostDate($this->flaggedPostArray),
                $this->getLastFlaggedPostDate($this->flaggedPostArray),
                $this->getFlaggedPostsPerYear($this->flaggedPostArray),
                $this->getBubbleData($this->flaggedPostArray),
                $test,
                self::getSortedFlaggedWordsArray($flaggedWordArray));

            print_r($report);

            return $report;
        }

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
     * @return array
     */
    private function setPostDataArray($data)
    {
        $postDataArray = array();

        foreach ($data as $post) {
            array_push($postDataArray, new PostData($post));
        }

        return $this->postDataArray = $postDataArray;
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

    public function jsonSerialize()
    {
        $props = array();
        foreach ($this as $key => $value) {
            $props[$key] = $value;
        }

        return $props;
    }

    /**
     * @return array of flagged posts
     */
    private function populateFlaggedPostArray()
    {
        $startTime = microtime(true);
        $flaggedPosts = array();
        // $isPlayerNames = false;

        foreach ($this->getPostDataArray() as $currentPost) {// iterate through each post
            $flaggedInstances = array(); //create flagdict word array
            foreach ($this->getDictionaryData() as $currentDict) { //iterate through each dictionary
                foreach ($currentDict->getDictionaryArray() as $dictWord) { //iterate through each word in dictionary
                    if ($currentDict->getName() === 'nflPlayers' || $currentDict->getName() === 'footballPhrases') { //if dictionary is nfl players
                        //strpos method will be used to match the dict word to the entire message as a string
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

    /**
     * @return float
     */
    public function getPercentageOfFlaggedPosts()
    {
        $allPostsTotal = sizeof($this->postDataArray);
        $flaggedPostTotal = sizeof($this->flaggedPostArray);

        return ($flaggedPostTotal / (float)$allPostsTotal);
    }

    /**
     * @param $flaggedPost
     * @return int This function returns the total weight of a post, by adding all the weights of all
     * the flagged words in a post.
     */
    public function getTotalWeightOfFlaggedPost($flaggedPost)
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
    public function getFlaggedWordsAndFrequency($flaggedPostArray)
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

    /**
     * @param $flaggedWordArray
     * @return mixed
     */
    public function getSortedFlaggedWordsArray($flaggedWordArray)
    {
        arsort($flaggedWordArray);

        return $flaggedWordArray;
    }

    /**
     * This function takes in a flagged post array and sorts it. This uses the bubble sort algoirthm.
     * I am using amperstand to signify I will be doing a pass by reference
     * @param $flaggedPostArray
     */
    public function sortFlaggedPostArray($flaggedPostArray)
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

    /**
     * @param $flaggedPostArray
     * @return float
     */
    public function getAverageWeightPerFlaggedPosts($flaggedPostArray)
    {
        $sum = 0.0;
        $numberOfFlaggedPosts = sizeof($flaggedPostArray);

        foreach ($flaggedPostArray as $flaggedPost) { //iterate through each post
            $sum += $flaggedPost->getTotalWeight();
        }
        return ($sum / $numberOfFlaggedPosts);
    }

    /**
     * @param $flaggedWordArrayWithFrequency
     * @param $nflTeamDictioanry
     * @return string
     */
    public function getFavoriteTeam($flaggedWordArrayWithFrequency, $nflTeamDictioanry)
    {
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

    /**
     * @param $flaggedPostArray
     * @return bool|string
     */
    public function getFirstFlaggedPostDate($flaggedPostArray)
    {
        $minDate = strtotime($flaggedPostArray[0]->getPostData()->getDate());
        foreach ($flaggedPostArray as $fpa) {
            $curDate = strtotime($fpa->getPostData()->getDate());
            if ($curDate < $minDate) {
                $minDate = $curDate;
            }
        }
        return date('d-m-Y', $minDate);
    }

    /**
     * @param $flaggedPostArray
     * @return bool|string
     */
    public function getLastFlaggedPostDate($flaggedPostArray)
    {
        $maxDate = strtotime($flaggedPostArray[0]->getPostData()->getDate());
        foreach ($flaggedPostArray as $fpa) {
            $curDate = strtotime($fpa->getPostData()->getDate());
            if ($curDate > $maxDate) {
                $maxDate = $curDate;
            }
        }
        return date('d-m-Y', $maxDate);
    }

    /**
     * @param $flaggedPostArray
     * @return float
     */
    public function getFlaggedPostsPerYear($flaggedPostArray)
    {
        echo "'\n'***ReportGenerator->getFlaggedPostsPerYear()***'\n'";

        $minDate = $this->getFirstFlaggedPostDate($flaggedPostArray);
        echo "'\n'minDate: $minDate'\n'";

        $maxDate = $this->getLastFlaggedPostDate($flaggedPostArray);
        echo "'\n'maxDate: $maxDate'\n'";

        echo "'\n'maxDate-stringToTime: " . strtotime($maxDate) . "'\n'";
        echo "'\n'minDate-stringToTime: " . strtotime($minDate) . "'\n'";

        $dateDiff = strtotime($maxDate) - strtotime($minDate);
        echo "'\n'dateDiff: $dateDiff'\n'";

        $totalYears = floor($dateDiff / (60 * 60 * 24 * 365));
        echo "'\n'totalYears: $totalYears'\n'";

        $postsPerYear = sizeof($flaggedPostArray) / $totalYears;
        echo "'\n'postsPerYear: $postsPerYear'\n'";

        return $postsPerYear;
    }

    /**
     * @return array
     */
    public function getIntervalFlaggedPosts()
    {
        $intervalFlaggedPostArray = array(); //Get empty array.
        $firstDate = $this->getFirstFlaggedPostDate($this->flaggedPostArray);
        $lastDate = $this->getLastFlaggedPostDate($this->flaggedPostArray);
        $dateRangeArray = $this->createDateRangeArray($firstDate, $lastDate);

        //Sort flagged post array before processing,
        usort($this->flaggedPostArray, function ($a, $b) {
            $t1 = strtotime($a->getPostData()->getDate());
            $t2 = strtotime($b->getPostData()->getDate());
            return $t1 - $t2;
        });


        foreach ($this->flaggedPostArray as $flaggedPost) { //Iterate through all flagged post data
            $date_m_d = date("m Y", strtotime($flaggedPost->getPostData()->getDate())); //convert flagged post data into
                                                                                        // 'Month Year" format.
            //Increment dateRangeArray frequncy
            $dateRangeArray[$date_m_d]++;
        }
        print_r(PHP_EOL."This is Line Graph Array: ".PHP_EOL);
        print_r($dateRangeArray);
        return array_values($intervalFlaggedPostArray);
    }

    public function createDateRangeArray($dateFrom, $dateTo)
    {
        $rangeArray = array(); //array that is being returned

        $dateTimeFrom = new DateTime($dateFrom);
        $dateTimeTo = new DateTime($dateTo);

        $firstDateMonth = $dateTimeFrom->format('m');
        $firstDateYear = $dateTimeFrom->format('Y');

        $lastDateMonth = $dateTimeTo->format('m');
        $lastDateYear = $dateTimeTo->format('Y');

        $isFirstIteration= True;

        //iterate through years
        for($year = $firstDateYear; $year <= $lastDateYear; $year++) {
            //If this is the first time the loop is iterating through months of the first year, set the first month
            //to the first month that was recieved from the first data. otherwise, set the first month as 1
            if($isFirstIteration) {
                $month = $firstDateMonth;
                $isFirstIteration = False;
            } else {
                $month = 1;
            }
            //iterate through months
            for ($i = $month; $i <= 12; $i++) {

                //Checks to see if the length string is less then 2. if it is, it adds a leading 0
                if (strlen($i) < 2) {
                    $formmattedMonth = strval(0) . $i;
                } else {
                    $formmattedMonth = $i;
                }

                //Stores, as string, Month as number and year as number
                $tempStringDate = $formmattedMonth . " " . strval($year);

                //Add new key to array (string date) and set value initial frequency, which is 0.
                $rangeArray[$tempStringDate] = 0;

                //If the current date matches the current month and current year, breaks out of the loop
                if ($i == $lastDateMonth && $year == $lastDateYear) {
                        break 2;
                }
            }
        }
        return $rangeArray;
    }

    /**
     * @param $startDate
     * @param $endDate
     * @param $flaggedPostArray
     * @return array
     */
//    public function getPostsWithinRange($startDate, $endDate, $flaggedPostArray)
//    {
//        $startDate = strtotime($startDate);
//        $endDate = strtotime($endDate);
//        $flaggedPostArrayInRange = array();
//        foreach ($flaggedPostArray as $fpa) {
//            $curDate = strtotime($fpa->getPostData()->getDate());
//            if ($curDate > $startDate && $curDate < $endDate) {
//                array_push($flaggedPostArrayInRange, $fpa);
//            }
//        }
//        return $flaggedPostArrayInRange;
//    }

    /**
     * @param $flaggedPostArray
     * @return array
     */
    public function getBubbleData($flaggedPostArray)
    {
        echo "'\n'INSIDE BUBBLE ARRAY'\n'";

        $bubbleDataArray = array();

        foreach ($flaggedPostArray as $flaggedPost) {
            foreach ($flaggedPost->getFlaggedWords() as $flaggedWordObject) {
                $dictName = $flaggedWordObject->getDataDictName();
                $flaggedWord = $flaggedWordObject->getFlaggedWord();

                //Check if key => value exists for dataDictionary
                if (!isset($bubbleDataArray[$dictName])) {
                    $bubbleDataArray[$dictName] = array();
                }

                //Sees if the word already exists in the flagged word array
                if (isset($bubbleDataArray[$dictName][$flaggedWord])) {
                    //if it exists, increment the word array
                    $bubbleDataArray[$dictName][$flaggedWord]++;
                    //if it doesn't exists, then add it to the word array
                } else {
                    $bubbleDataArray[$dictName][$flaggedWord] = 1;
                }
            }
        }
        
        return $bubbleDataArray;
    }
}

/**
 * Class FlaggedPost
 */
class FlaggedPost implements JsonSerializable
{
    private $postData; //an instance of Post Data object
    private $flaggedWords = array(); //a list of flagged words and associated information per post

    public function jsonSerialize()
    {
        $props = array();

        foreach ($this as $key => $value) {
            $props[$key] = $value;
        }
        return $props;
    }

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

/**
 * Class FlaggedWord
 */
class FlaggedWord implements JsonSerializable
{
    private $dataDictName; //corresponding dict name
    private $dictWeight; //corresponding weight per word
    private $flaggedWord; //actual flagged word string

    public function jsonSerialize()
    {
        $props = array();

        foreach ($this as $key => $value) {
            $props[$key] = $value;
        }
        return $props;
    }

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