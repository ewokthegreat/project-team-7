<?php
/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/24/2016
 * Time: 12:57 AM
 */
ini_set('display_errors',1);
error_reporting(E_ALL);

define('__PROJECT_ROOT__', $_SERVER['DOCUMENT_ROOT']);
define('__DICTIONARY_DATA__', __PROJECT_ROOT__ . '/' . 'dictionaries');
define('__PHP_SCRIPTS__', __PROJECT_ROOT__ . '/' . 'php');
define('__USER_DATA__', __PROJECT_ROOT__ . '/' . '.raw_user_data');

//include_once __PHP_SCRIPTS__ . '/' . 'DBObject.php';
//include_once __PHP_SCRIPTS__ . '/' . 'PostData.php';
//include_once __PHP_SCRIPTS__ . '/' . 'DictionaryData.php';
//include_once __PHP_SCRIPTS__ . '/' . 'ReportGenerator.php';
//include_once __PHP_SCRIPTS__ . '/' . 'Scan.php';
//include_once __PHP_SCRIPTS__ . '/' . 'Applicant.php';

include_once 'DatabaseConnector.php';

//../.raw_user_data/10208453844209830/16.04.24.14.40.00__10208453844209830.ace
//$aceFile = file_get_contents('../.raw_user_data/10208453844209830/16.04.23.22.31.03__10208453844209830.ace');
$aceFile = file_get_contents('../.raw_user_data/10208453844209830/16.04.24.14.40.00__10208453844209830.ace');

$rawAce = json_decode($aceFile);

$flaggedPostArray = $rawAce->sortedByWeightFlaggedPostsArray;

$freqArray = populateFreqArray($flaggedPostArray);

//print_r(json_encode($freqArray));
print_r($aceFile);
/**
 * @param $msg
 */
function trace($msg) {
    print_r(json_encode($msg));
}

/**
 * @param $data
 * @return array
 */
function createEmptyFreqArray($data) {
    $frequencyArray = array();

    for($i = 0; $i < sizeof($data); $i++) {
        $currentPost = $data[$i];
        $currentPostDate = $currentPost->postData->date;

        $arr = array();

        $parsedDate = DateTime::createFromFormat('Y-m-d G:i:s.u', $currentPostDate);
        $currentYear = $parsedDate->format('y');
        $currentMonth = $parsedDate->format('m');

        if(!isset($frequencyArray[$currentYear])) {
            for($x = 1; $x < 13; $x++) {
                $arr[$x] = 0;
            }
            $frequencyArray[$currentYear] = $arr;
        }
    }

    return $frequencyArray;
}

/**
 * @param $data
 * @return array
 */
function populateFreqArray($data) {
    $arr = createEmptyFreqArray($data);

    for($i = 0; $i < sizeof($data); $i++) {
        $currentPost = $data[$i];
        $currentPostDate = $currentPost->postData->date;

        $parsedDate = DateTime::createFromFormat('Y-m-d G:i:s.u', $currentPostDate);
        $currentYear = $parsedDate->format('y');
        $currentMonth = $parsedDate->format('m');

        foreach($arr as $year => $months) {
            foreach($months as $month => $count) {
                if($currentYear == $year) {
                    if($currentMonth == $month) {
                        $arr[$year][$month]++;
                    }
                }
            }
        }

    }

    return($arr);
}