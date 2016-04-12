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

print_r($final_array);

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

function getMessageSubstring($string) {
    //delimeter is double quotes
    $newArray = explode("\"", $string);
    return $newArray[3];
}

function getIdSubstring($string) {
    $newArray = explode("\"id\":\"", $string);
    $idString = rtrim($newArray[1], '\"');
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
