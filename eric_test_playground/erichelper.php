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

define('__PROJECT_ROOT__', '/home/ewoktheg/public_html/spider/eric_test_playground');

$pathToData = __PROJECT_ROOT__ . "/.raw_user_data/10208453844209830/16.04.10.23.52.59__10208453844209830.json";
$pathToDictionary = __PROJECT_ROOT__ . "/dictionaries/SpiderWordBank.csv";

//Get the raw post data
$json = file_get_contents($pathToData);
//Convert the raw post data from json to php
$data = json_decode($json);
//Create the dictionary
$dictionary = new DictionaryData($pathToDictionary);
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
        print_r($dictionary->getDictionaryArray());

        //Create the post data from raw data.
        $postDataArray = array();
        foreach ($data as $obj) {
            array_push($postDataArray, new PostData($obj));
        }

        foreach($postDataArray as $post) {
            print_r($post->getWordArray());
        }
        ?>
    </pre>
</div>
</body>
</html>