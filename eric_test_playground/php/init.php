<?php
/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/9/2016
 * Time: 9:23 PM
 * Description: This class serves as the gateway to our app. The first step is create
 * new DictionaryData objects and pass them into our AppEngine. From here, this class
 * iniitalizes the ReportGenerator class and generates a report.
 */
ini_set('display_errors',1);
error_reporting(E_ALL);
define('__PROJECT_ROOT__', $_SERVER['DOCUMENT_ROOT']);
define('__FB_API_PATH__', '/libs/facebook-php-sdk-v4-5.0.0/src/Facebook/autoload.php');
define('__DICTIONARY_DATA__', __PROJECT_ROOT__ . '/' . 'dictionaries');
define('__USER_DATA__', __PROJECT_ROOT__ . '/.raw_user_data');

require_once __PROJECT_ROOT__ .  __FB_API_PATH__;
include_once 'AppEngine.php';
include_once 'DBObject.php';
include_once 'PostData.php';
include_once 'DictionaryData.php';
include_once 'ReportGenerator.php';
include_once 'Scan.php';
include_once 'Applicant.php';
include_once 'DatabaseConnector.php';

/**
 * Trace is a debugging function.
 * @param $msg that will be printed to the console window.
 * @return void.
 */
function trace($msg) {
    echo "'\n'****$msg*****'\n'";
}
//Create empty dictionary array
$dictionariesArray = array();

//Create new data dictionary objects from CSV file
$footballPhrases = new DictionaryData("footballPhrases", __PROJECT_ROOT__ . "/dictionaries/football_phrases.csv",5);
$footballTerms = new DictionaryData("footballTerms", __PROJECT_ROOT__ . "/dictionaries/SpiderWordBank.csv",5);
$nflLocations = new DictionaryData("nflLocations",  __PROJECT_ROOT__ . "/dictionaries/nfl_city_state.csv",1);
$nflStadiums = new DictionaryData("nflStadiums",  __PROJECT_ROOT__ . "/dictionaries/nfl_stadiums.csv",2);
$nflTeams = new DictionaryData("nflTeams",  __PROJECT_ROOT__ . "/dictionaries/nfl_team_names.csv", 4);
$nflPlayers = new DictionaryData("nflPlayers",  __PROJECT_ROOT__ . "/dictionaries/nfl_top_players_2015csv.csv",5);

//Push all DictionaryData objects to dictionariesArray
array_push($dictionariesArray,$footballPhrases);
array_push($dictionariesArray,$footballTerms);
array_push($dictionariesArray,$nflLocations);
array_push($dictionariesArray,$nflStadiums);
array_push($dictionariesArray,$nflTeams);
array_push($dictionariesArray,$nflPlayers);

//Instantiate AppEngine
$appEngine = new AppEngine($dictionariesArray);

//Initialize AppEngine
$appEngine->init();

//Get reference of ReportGenerator from AppEngine
$reportGenerator = $appEngine->getReportGenerator();

//Generates and returns the report
$report = $reportGenerator->init();