<?php
/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/11/2016
 * Time: 10:17 PM
 */

ini_set('display_errors',1);
error_reporting(E_ALL);

include_once 'php/PostData.php';
include_once 'php/DictionaryData.php';
include_once 'php/ReportGenerator.php';

define('__PROJECT_ROOT__', '/home/ewoktheg/public_html/spider/eric_test_playground');

$pathToData = __PROJECT_ROOT__ . "/.raw_user_data/10208453844209830/16.04.10.23.52.59__10208453844209830.json";
$pathToDictionary = __PROJECT_ROOT__ . "/dictionaries/SpiderWordBank.csv";

//Get the raw post data
$json = file_get_contents($pathToData);
//Convert the raw post data from json to php
$data = json_decode($json);
?>
<!doctype HTML>
<html>

<head>
    <title>Eric's Helper</title>
</head>

<body>

<h1><?='Hello, world!'?></h1>

<div>
    <pre>
        <?php
        //Create the dictionary array
        $dictionaries = array();

        $footballTerms = new DictionaryData("footballTerms", __PROJECT_ROOT__ . "/dictionaries/SpiderWordBank.csv",5);
        $nflLocations = new DictionaryData("nflLocations",  __PROJECT_ROOT__ . "/dictionaries/nfl_city_state.csv",1);
        $nflStadiums = new DictionaryData("nflStadiums",  __PROJECT_ROOT__ . "/dictionaries/nfl_stadiums.csv",2);
        $nflTeams = new DictionaryData("nflTeams",  __PROJECT_ROOT__ . "/dictionaries/nfl_team_names.csv", 4);
        $nflPlayers = new DictionaryData("nflPlayers",  __PROJECT_ROOT__ . "/dictionaries/nfl_top_players_2015csv.csv",3);

        array_push($dictionaries,$footballTerms);
        array_push($dictionaries,$nflLocations);
        array_push($dictionaries,$nflStadiums);
        array_push($dictionaries,$nflTeams);
        array_push($dictionaries,$nflPlayers);



        //Create the report
        $report = new ReportGenerator($dictionaries, $data);
        ?>
    </pre>
</div>
</body>
</html>