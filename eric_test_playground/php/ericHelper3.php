<?php
/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/20/2016
 * Time: 3:14 PM
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
require_once('../libs/Mustache/Autoloader.php');
Mustache_Autoloader::register();
$mustache = new Mustache_Engine;
$postTemplate =
    "Date: {{date}}".PHP_EOL.
    "Post Text: {{message}}".PHP_EOL.
    "Descriptive Text: {{story}}".PHP_EOL.
    "Flagged Words:".PHP_EOL.
    "{{#flaggedWords}}
    {{#.}}
    {{flaggedWord}}".PHP_EOL.
    "{{/.}}
    {{/flaggedWords}}";

function trace($msg) {
    echo "<pre>****$msg*****</pre>";
}
$db = new DatabaseConnector();
///eric_test_playground/.raw_user_data/10208453844209830/16.04.22.22.04.56__10208453844209830.ace

$aceFile = file_get_contents('../.raw_user_data/10208453844209830/16.04.22.22.04.56__10208453844209830.ace');
$rawAce = json_decode($aceFile);

$userID = $rawAce->userID;
$flaggedPostArray = $rawAce->sortedByWeightFlaggedPostsArray;
$dateString = generateDateString('y.m.d.G.i.s', $rawAce->dateGenerated);
$user = $db->selectApplicant($userID);

function generateDateString($format, $date) {
    $parsedDate = date_parse_from_format($format, $date);
    $dateString = $parsedDate['month'] . '-' . $parsedDate['day'] . '-' . $parsedDate['year'];

    return $dateString;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>

    <link rel="stylesheet" href="../css/report-beta.css">
</head>
<body>

<div id="profile-summary">
    <div id="profile-picture">
        <img src="<?=$user['profilePicture']?>"/>
    </div><!--/#profile-picture-->
    <div id="profile-info">
        <h3 id="profile-name"><?=$user['firstName'] . ' ' . $user['lastName']?></h3>
        <div id="profile-email"><?=$user['email']?></div>
        <div id="profile-scan-date"><?=$dateString?></div>
        <a id="profile-link" href="<?=$user['profileLink']?>">FB Profile</a>
    </div><!--/#profile-info-->
</div><!--/#profile-summary-->

<div id="post-data-summary">
<h1>Summary</h1>
    <dl class="inline">
    <?php
    $skip = array('userID', 'pathToReportData', 'dateGenerated',
                  'sortedByWeightFlaggedPostsArray', 'sortedByWeightFlaggedWordsAndFrequencyArray', 'bubbleGraphData');

    $flaggedPostDatesFormat = 'd-m-Y';
    $flaggedPostDates = array('firstFlaggedPostDate', 'lastFlaggedPostDate');

    foreach($rawAce as $key => $value) {
        if(in_array($key, $skip)) {
            continue;

        } else if(in_array($key, $flaggedPostDates)) {
            $dateString = generateDateString($flaggedPostDatesFormat, $value);

            echo "<dt>$key</dt><dd>$dateString</dd>";
        } else {
            echo "<dt>$key</dt><dd>$value</dd>";
        }
    }
    ?>
    </dl>
</div><!--/#post-data-summary-->

<div id="post-data-detail">
<h1>Detail</h1>
</div><!--/#post-data-detail-->

<div id="test-output">
    <pre>
        <?php
        echo "".PHP_EOL;
        $counter = 1;
        foreach ($flaggedPostArray as $currentPost){
            echo "Post #" . $counter . PHP_EOL;
            echo $mustache->render($postTemplate,$currentPost->postData);
            $counter++;
        }
        print_r($flaggedPostArray);
        ?>
    </pre>
</div><!--/#test-output-->

<script type="text/javascript" src="../js/lib/d3.js"></script>
<script type="text/javascript" src="../js/report.js"></script>
</body>
</html>