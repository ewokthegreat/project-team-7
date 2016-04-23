<?php
/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/9/2016
 * Time: 9:23 PM
 * Upder: NOW
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
function trace($msg) {
    echo "'\n'****$msg*****'\n'";
}
$dictionaries = array();
$footballTerms = new DictionaryData("footballTerms", __PROJECT_ROOT__ . "/dictionaries/SpiderWordBank.csv",5);
$nflLocations = new DictionaryData("nflLocations",  __PROJECT_ROOT__ . "/dictionaries/nfl_city_state.csv",1);
$nflStadiums = new DictionaryData("nflStadiums",  __PROJECT_ROOT__ . "/dictionaries/nfl_stadiums.csv",2);
$nflTeams = new DictionaryData("nflTeams",  __PROJECT_ROOT__ . "/dictionaries/nfl_team_names.csv", 4);
$nflPlayers = new DictionaryData("nflPlayers",  __PROJECT_ROOT__ . "/dictionaries/nfl_top_players_2015csv.csv",5);
array_push($dictionaries,$footballTerms);
array_push($dictionaries,$nflLocations);
array_push($dictionaries,$nflStadiums);
array_push($dictionaries,$nflTeams);
array_push($dictionaries,$nflPlayers);
$app = new AppEngine($dictionaries);
$app->init();
//$reportGenerator = $app->getReportGenerator();
//$report = $reportGenerator->init();