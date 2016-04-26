<?php

/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/12/2016
 * Time: 5:04 AM
 * This class is responsible for generating the report that will be converted to
 * a raw JSON object. This JSON object will will be used to display the report
 * to the front end.
 */

include_once 'Report.php';

class ReportGenerator implements JsonSerializable
{
    private $dictionaryData;
    private $postDataArray;
    private $flaggedPostArray;
    private $userId;
    private $pathToData;

    /**
     * ReportGenerator constructor.
     */
    public function __construct()
    {
    }

    /**
     * Sets the $path and and $rawPostData in this object.
     */
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

    /**
     * @return Report the report object that the ReportGenerator generated.
     */
    public function init()
    {
        $this->fetchRawPostData();
        $this->populateFlaggedPostArray();

        return $this->getReportObject();
    }

    /**
     * @return Report the instance of of report object.
     */
    private function getReportObject()
    {
        //Parses information from pathToData string to send through to ReportObject
        $pathToDataArray = explode('/', $this->pathToData);
        $size = sizeof($pathToDataArray);
        $timeStamp = $pathToDataArray[$size - 1];
        $cleanTimeStamp = explode('__', $timeStamp);
        $finalTimeStamp = $cleanTimeStamp[0];

        //Sorts flagged post array prior to creating reportObject
        self::sortFlaggedPostArray($this->flaggedPostArray);

        //Creates fagged words prior to creating ReportObject
        $flaggedWordArray = self::getFlaggedWordsAndFrequency($this->flaggedPostArray);
        $sortedByWeightFlaggedPostArray = $this->flaggedPostArray;

        //Gets the nflTeams dict. This is used to figure out the applicant's favorite team.
        $nflTeamDict = null;
        foreach ($this->getDictionaryData() as $currentDict) { //iterate through each dictionary
            if ($currentDict->getName() === 'nflTeams') { //if dictionary is nfl players
                $nflTeamDict = $currentDict;
                break;
            }
        }

        //Basically, if  no posts are flagged, creates a "default" report to
        //pass to the front end
        if (!isset($sortedByWeightFlaggedPostArray) || empty($sortedByWeightFlaggedPostArray)) {
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
                array("Empty"),
                array("Empty"));
            return $report;
        } else {
            //Generate a actual report with actual content.
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
                $sortedByWeightFlaggedPostArray,
                self::getSortedFlaggedWordsArray($flaggedWordArray),
                $this->getIntervalFlaggedPosts());

            print_r($report);

            return $report;
        }
    }


    /**
     * Used to help convert this class into JSON object.
     * @return array
     */
    public function jsonSerialize()
    {
        $props = array();
        foreach ($this as $key => $value) {
            $props[$key] = $value;
        }

        return $props;
    }

    /**
     * This is the "meat" of our application. This method sorts through all the posts of the
     * users and created FlaggedPosts objects, which consists of Flagged Posts and associated
     * FlaggedWords
     * @return array of flagged posts
     */
    private function populateFlaggedPostArray()
    {
        //used to detrmine the actual time of the algorithm to pull posts, if necessary.
//        $startTime = microtime(true);
        $flaggedPostsArray = array();

        foreach ($this->getPostDataArray() as $currentPost) {// iterate through each post
            $flaggedWordsArray = array(); //create Flagged Words array
            foreach ($this->getDictionaryData() as $currentDict) { //iterate through each dictionary
                foreach ($currentDict->getDictionaryArray() as $dictWord) { //iterate through each word in dictionary
                    //if dictionary is nfl players or football phrases, use strpos method for multiple words
                    if ($currentDict->getName() === 'nflPlayers' || $currentDict->getName() === 'footballPhrases') {
                        //strpos method will be used to match the dict word to the entire message as a string
                        if (strpos(strtolower($currentPost->getAllWordsAsString()), strtolower($dictWord)) !== FALSE) {
                            array_push($flaggedWordsArray,
                                new FlaggedWord($currentDict->getName(),
                                    $currentDict->getWeight(),
                                    strtolower($dictWord)));
                        }
                    } else { //the dictionary is not a nfl players dictionary or football phrases, can match on single word
                        foreach ($currentPost->getWordArray() as $currentPostWord) { //iterate through each word in the post
                            if (strtolower($currentPostWord) === strtolower($dictWord)) { //match word by word
                                array_push($flaggedWordsArray,
                                    new FlaggedWord($currentDict->getName(),
                                        $currentDict->getWeight(),
                                        strtolower($dictWord)));
                                //break; //Uncomment this if you want to avoid duplicate dictionary words per post.
                            }
                        }
                    }
                }
            }
            if (!empty($flaggedWordsArray)) { //if the flagged words array is not empty, created a new Flagged Post object.
                array_push($flaggedPostsArray,
                    new FlaggedPost($currentPost, $flaggedWordsArray));
            }
        }

        //Uses to help us determine how long our main algorithm is taking, in case there is any chance for improvements.
//        echo 'Elapsed Time: ' . (microtime(true) - $startTime) . "seconds";
        $this->flaggedPostArray = $flaggedPostsArray;
        return $flaggedPostsArray;
    }

    /**
     * Calculates a percentage of Flagged Posts to all posts.
     * @return float the percentage of flagged posts to all posts.
     */
    public function getPercentageOfFlaggedPosts()
    {
        $allPostsTotal = sizeof($this->postDataArray);
        $flaggedPostTotal = sizeof($this->flaggedPostArray);

        return ($flaggedPostTotal / (float)$allPostsTotal);
    }

    //can probably delete this, it is in FlaggedWords class.
//    /**
//     * This method gets the weight
//     * @param $flaggedPost
//     * @return int This function returns the total weight of a post, by adding all the weights of all
//     * the flagged words in a post.
//     */
//    public function getTotalWeightOfFlaggedPost($flaggedPost)
//    {
//        $flaggedWordsList = $flaggedPost->getFlaggedWords();
//
//        $totalWeight = 0;
//
//        foreach ($flaggedWordsList as $flaggedWord) {
//            $totalWeight += $flaggedWord->getDictWeight();
//        }
//
//        return $totalWeight;
//    }

    /**
     * This method receives all the Flagged Posts and returns an array of flagged words with frequency
     * @param $flaggedPostArray array of FlaggedPost objects
     * @return array of flagged word as key and frequency of word as value.
     */
    public function getFlaggedWordsAndFrequency($flaggedPostArray)
    {
        $flaggedWordsArray = array(); //create empty flagged words array

        foreach ($flaggedPostArray as $flaggedPost) { //iterate through all posts
            foreach ($flaggedPost->getFlaggedWords() as $flaggedWordObject) { //iterate through all words in that particular post
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
     * This method received a flagged words array and sort by frequency, in descending order.
     * @param $flaggedWordArray array Flagged Words array
     * @return array of FlaggedWords array sorted by frequency, in descending order.
     */
    public function getSortedFlaggedWordsArray($flaggedWordArray)
    {
        arsort($flaggedWordArray);
        return $flaggedWordArray;
    }

    /**
     * This function takes in a flagged post array and sorts it, in descending order by weight.
     * This method uses the bubble sort algorithm to sort the array..
     * @param $flaggedPostArray array of Flagged Ports
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
     * Method that takes in a Flagged Posts array and returns the average weight of that list of Flagged Posts
     * @param $flaggedPostArray array of Flagged Posts
     * @return float the average of flagged posts.
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
     * Primitive method that tried to guess the favorite team by returning the first flagged word it encounters
     * in the Flagged Word Array that is sorted by frequency.
     * @param $flaggedWordArrayWithFrequency array of flagged words with frequency.
     * @param $nflTeamDictionary DictionaryData that will be used to figured out the favorite team.
     * @return string of the favorite team name, or no favorite team if nothing is detected.
     */
    public function getFavoriteTeam($flaggedWordArrayWithFrequency, $nflTeamDictionary)
    {
        arsort($flaggedWordArrayWithFrequency); //sort flagged words first

        //create array containing all keys
        $keyArray = array_keys($flaggedWordArrayWithFrequency);

        foreach ($keyArray as $key) { //iterate through every key in word array
            foreach ($nflTeamDictionary->getDictionaryArray() as $dictWord) { //cycle through each word in the dictionary
                if (strtolower($key) === strtolower($dictWord)) {
                    return $dictWord;
                }
            }
        }
        return "No favorite team";
    }

    /**
     * Method that returns the date of the first flagged post.
     * @param $flaggedPostArray array of Flagged Post array.
     * @return bool|string the date of the first flagged post.
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
     * Method that returns the date of the last flagged post.
     * @param $flaggedPostArray  array of Flagged Post array.
     * @return bool|string the date of the last flagged post.
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
     * Method that gets average of flagged posts per year
     * @param $flaggedPostArray array Flagged post array
     * @return float the average of flagged posts per year.
     */
    public function getFlaggedPostsPerYear($flaggedPostArray)
    {
        $minDate = $this->getFirstFlaggedPostDate($flaggedPostArray);
        $maxDate = $this->getLastFlaggedPostDate($flaggedPostArray);

        $dateDiff = strtotime($maxDate) - strtotime($minDate);
        $totalYears = floor($dateDiff / (60 * 60 * 24 * 365));

        $postsPerYear = sizeof($flaggedPostArray) / $totalYears;
        return $postsPerYear;
    }

    /**
     * Calculates the frequency of flagged posts per month.
     * @return array an array of flagged posts per month, starting with the first flagged post and
     * ending with the last flagged post.
     */
    public function getIntervalFlaggedPosts()
    {
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
            //Increment dateRangeArray frequency
            $dateRangeArray[$date_m_d]++;
        }
        return $dateRangeArray;
    }

    /**
     * Method to create empty date range array. This will be used to populate our
     * post per frequency array.
     * @param $dateFrom string date of first post
     * @param $dateTo string date of last post
     * @return array of month and year as the key and 0 for values
     */
    private function createDateRangeArray($dateFrom, $dateTo)
    {
        $rangeArray = array(); //array that is being returned

        //Convert to new DateTime objects.
        $dateTimeFrom = new DateTime($dateFrom);
        $dateTimeTo = new DateTime($dateTo);

        //Pull month and year from first post date
        $firstDateMonth = $dateTimeFrom->format('m');
        $firstDateYear = $dateTimeFrom->format('Y');

        //Pull month and year from second first date
        $lastDateMonth = $dateTimeTo->format('m');
        $lastDateYear = $dateTimeTo->format('Y');

        $isFirstIteration = True;

        //iterate through years
        for($year = $firstDateYear; $year <= $lastDateYear; $year++) { //for loop to increment years
            //If this is the first time the loop is iterating through months of the first year, set the first month
            //to the first month that was received from the first data. otherwise, set the first month as 1
            if($isFirstIteration) {
                $month = $firstDateMonth;
                $isFirstIteration = False;
            } else {
                $month = 1; //resets month to 1 every time there is a new year
            }
            //iterate through months
            for ($i = $month; $i <= 12; $i++) {

                //Checks to see if the length string is less then 2. if it is, it adds a leading 0. this is
                //mainly used for formatting when executing the getIntervalFlaggedPosts method.
                if (strlen($i) < 2) {
                    $formattedMonth = strval(0) . $i;
                } else {
                    $formattedMonth = $i;
                }

                //Stores, as string, Month as number and year as number
                $tempStringDate = $formattedMonth . " " . strval($year);

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
     * Used to format the flagged words account into a usable information to be used
     * to display the flagged words for the bubble data graph.
     * @param $flaggedPostArray array of Flagged POst
     * @return array an array of flagged words and frequency which will be used to create
     * the bubble graph.
     */
    public function getBubbleData($flaggedPostArray)
    {
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
}

/**
 * Private inner class. This class will store all the flagged posts and associated
 * flagged words.
 * Class FlaggedPost
 */
class FlaggedPost implements JsonSerializable
{
    private $postData; //an instance of Post Data object
    private $flaggedWords = array(); //a list of flagged words and associated information per post

    /**
     * FlaggedPost constructor.
     * @param $postData PostData the post that is being flagged.
     * @param $flaggedWords FlaggedWord[] list of flagged words
     */
    public function __construct($postData, $flaggedWords)
    {
        $this->setPostData($postData);
        $this->setFlaggedWords($flaggedWords);
    }

    /**
     * Used to help convert this class into JSON object.
     * @return array
     */
    public function jsonSerialize()
    {
        $props = array();

        foreach ($this as $key => $value) {
            $props[$key] = $value;
        }
        return $props;
    }

    /**
     * This method calculates the total weight of the post by adding all the
     * words and phrases that are found in the post and adding them together.
     * @return int the total weight of the post.
     */
    public function getTotalWeight()
    {
        $totalWeight = 0;

        foreach ($this->getFlaggedWords() as $flaggedWord) {
            $totalWeight += $flaggedWord->getDictWeight();
        }
        return $totalWeight;
    }

    /**
     * @return mixed
     */
    public function getPostData()
    {
        return $this->postData;
    }

    /**
     * @param mixed $postData
     */
    public function setPostData($postData)
    {
        $this->postData = $postData;
    }

    /**
     * @return array
     */
    public function getFlaggedWords()
    {
        return $this->flaggedWords;
    }

    /**
     * @param array $flaggedWords
     */
    public function setFlaggedWords($flaggedWords)
    {
        $this->flaggedWords = $flaggedWords;
    }
}

/**
 * Class FlaggedWord
 * Description: This is a private inner class that is used to store all
 * the flagged words that is associated with a flagged post.
 */
class FlaggedWord implements JsonSerializable
{
    private $dataDictName; //corresponding dict name
    private $dictWeight; //corresponding weight per word
    private $flaggedWord; //actual flagged word string

    /**
     * FlaggedWord constructor.
     * @param $dataDictName
     * @param $dictWeight
     * @param $flaggedWord
     */
    public function __construct($dataDictName, $dictWeight, $flaggedWord)
    {
        $this->setDataDictName($dataDictName);
        $this->setDictWeight($dictWeight);
        $this->setFlaggedWord($flaggedWord);
    }

    /**
     * Used to help convert this class into JSON object.
     * @return array
     */
    public function jsonSerialize()
    {
        $props = array();

        foreach ($this as $key => $value) {
            $props[$key] = $value;
        }
        return $props;
    }

    /**
     * @return mixed
     */
    public function getDataDictName()
    {
        return $this->dataDictName;
    }

    /**
     * @param mixed $dataDictName
     * @return FlaggedWord
     */
    public function setDataDictName($dataDictName)
    {
        $this->dataDictName = $dataDictName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDictWeight()
    {
        return $this->dictWeight;
    }

    /**
     * @param mixed $dictWeight
     * @return FlaggedWord
     */
    public function setDictWeight($dictWeight)
    {
        $this->dictWeight = $dictWeight;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFlaggedWord()
    {
        return $this->flaggedWord;
    }

    /**
     * @param mixed $flaggedWord
     * @return FlaggedWord
     */
    public function setFlaggedWord($flaggedWord)
    {
        $this->flaggedWord = $flaggedWord;
        return $this;
    }
}