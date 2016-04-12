<?php
/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/11/2016
 * Time: 10:17 PM
 */

ini_set('display_errors',1);
error_reporting(E_ALL);

//require_once '/home/ewoktheg/public_html/spider/php/ProcessJSON.php';
//require_once '/home/ewoktheg/public_html/spider/php/ProcessFBPosts.php';
require_once 'ProcessFBPosts.php';
require_once 'WordBank.php';

$pathToData = "/home/ewoktheg/public_html/spider/eric_test_playground/.raw_user_data/10208453844209830/16.04.10.23.52.59__10208453844209830.json";

$data = file_get_contents($pathToData);

//echo($data);


#echo("hello from adam\"\n");

//echo(gettype($data))

//convert string into an array of posts
//key values, message
//id => message
$dataArray = explode('},{', $data);

$key_id_array = [];
$value_message_array = [];

# get all keys (ids) into one array
foreach($dataArray as $bigSubstring) {
    array_push($key_id_array, getIdSubstring($bigSubstring));
}

# get all value (message) into one array
foreach($dataArray as $bigSubstring) {
    array_push($value_message_array, getMessageSubstring($bigSubstring));
}

// Now combine both arrays here
$final_array = array_combine($key_id_array, $value_message_array);

//print_r($final_array);

//foreach ($dataArray as $subString) {
//
////    print_r(getMessageSubstring($subString));
//    print_r(getIdSubstring($subString));
////    print_r("\n");
//}


//good, this makes an array but now we need to get the values
//method to pull out the message
//message to pull out the ID

//Pass in a string and return the message
//sample input:
//"message":"Amen.","created_time":{"date":"2016-04-10 21:22:29.000000","timezone_type":1,"timezone":"+00:00"},"id":"10208453844209830_10208832407153667"


//Ok, so now we want to pass in the the final array, and each individaul word bank and we wanted to return a
//2 dimensional array. The key is will remain the same, but the value will be an array containging the
//original message in element 1 and the rest of the element indicates the number of hits per word bank
//here is an example element in array:
//original: [10208453844209830_10208832407153667] => Amen.
//after: [10208453844209830_10208832407153667] => array('Amen', 0, 0, 0, 0, 0);

//so here would be the function call signature
//function create2Darray($finalArray, $2DarrayWordBank)

//declare 2D array
//$multiArray = [[]];

$multiArray = get2dArrayWordBank();

#print Array to test....cannot test this method, so I will assume it is correct
foreach ($multiArray as $singleArray) {
    print_r($singleArray);
}


function flagPosts($formattedMessageArray, $multi2dWordbankArray) {
    //logic will go here

    //break message into word array; essentially use explode function and delimit on spaces

    //compare word to each word bank if hit exists, increment that approriate array element and break out early
    //and analyze next word

    //once one message is done, incremement to message and repeat same process

    //psuedocode::
    /**
     * //first create a 2 dimensional array based on the side of the $multi2dWordbankArray. Example below with first element:
     * //after: [10208453844209830_10208832407153667] => array('Amen', 0, 0, 0, 0, 0);
     * //lets call this flagMessagesArray
     *
     *
     * for message in all messages //for all messages in word bank
     *    word_array = getWordArray(message) //convert specific message to word array delimeted with spaces
     *    for (words in word_array) //another loop to cycle through words in a specified message
     *       for (individualWordBank in multi2dWordBank)
     *         if (word is in individualwordbank)
     *             increment word bank element
     *             break out of for loop
     *         //if word not found in first word bank, the loop will keep iterating through all word banks until a
     *         //match is found, or the whole for loop is exahusted
     *
     *
     * return flagMessagesArray
     */
}


/**
 * @param $multiArray
 */
function get2dArrayWordBank()
{
    $multiArray = [[]]; //array that will be returned

    //adding arrays using key values pairs
    $multiArray["nfl_city_state"] = WordBankHandler::getWordBank('nfl_city_state.csv');
    $multiArray["nfl_last_names"] = WordBankHandler::getWordBank('nfl_last_names.csv');
    $multiArray["nfl_stadiums"] = WordBankHandler::getWordBank('nfl_stadiums.csv');
    $multiArray["nfl_team_names"] = WordBankHandler::getWordBank('nfl_team_names.csv');
    $multiArray["nfl_top_players_2015"] = WordBankHandler::getWordBank('nfl_top_players_2015csv.csv');

    return $multiArray;
}

//get2DarrayWordBank($multiArray);

function getMessageSubstring($string) {
    //delimeter is double quotes
    $newArray = explode("\"", $string);
    return $newArray[3];
}

function getIdSubstring($string) {
    $newArray = explode("\"id\":\"", $string);
    $idString = rtrim($newArray[1], '\"'); //remove any uncessary double quotes
    return $idString;
}

?>
<!doctype HTML>
<html>
<head>

</head>
<body>

</body>
</html>
