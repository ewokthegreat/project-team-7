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

define('__PROJECT_ROOT__', '/home/ewoktheg/public_html/spider/ee_adam_playground2');

$pathToData = "/home/ewoktheg/public_html/spider/eric_test_playground/.raw_user_data/10208453844209830/16.04.10.23.52.59__10208453844209830.json";
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
        //Create the dictionary
        $dictionary = new DictionaryData($pathToDictionary);
        //Create the report
        $report = new ReportGenerator($dictionary, $data);
        ?>
    </pre>
</div>
</body>
</html>