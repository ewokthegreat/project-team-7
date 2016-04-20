<?php
/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/11/2016
 * Time: 10:17 PM
 */

ini_set('display_errors',1);
error_reporting(E_ALL);

include_once 'php/DBObject.php';
include_once 'php/PostData.php';
include_once 'php/DictionaryData.php';
include_once 'php/ReportGenerator.php';
include_once 'php/Scan.php';
include_once 'php/DatabaseConnector.php';
include_once 'php/Report.php';

define('__PROJECT_ROOT__', $_SERVER['DOCUMENT_ROOT']);

$userId = '10108215518995891';
$fileName = '16.04.12.20.44.40__10108215518995891' . '.json';
$pathToData = __PROJECT_ROOT__ . "/.raw_user_data" . '/' . $userId . '/' . $fileName;

//$scan = new Scan($fileName);

//Get the raw post data
$json = file_get_contents($pathToData);
//Convert the raw post data from json to php
$data = json_decode($json);

function trace($msg) {
    echo "'\n'****$msg*****'\n'";
}
?>

<!doctype HTML>
<html>

<head>
    <title>Eric's Helper</title>
</head>

<body>

<h1><?='Spider Search Results'?></h1>

<div>
    <pre>
        <?php
        //Create the dictionary array
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

        //Create the report
        $reportGenerator = new ReportGenerator();
        $reportGenerator->setDictionaryData($dictionaries);
        $reportGenerator->setPathToData($pathToData);
        $report = $reportGenerator->init(); //<-----Here is the report object. please take it

        print_r($report); // <--------------------Report object is be printed out
        ?>
    </pre>
</div>
</body>
</html>