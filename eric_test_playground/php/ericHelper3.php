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


function trace($msg) {
    echo "<pre>****$msg*****</pre>";
}
$db = new DatabaseConnector();

$aceFile = file_get_contents('../.raw_user_data/10208453844209830/16.04.20.15.33.51__10208453844209830.ace');
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

    <link rel="stylesheet" href="../css/repot-beta.css">
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
    <dl>
    <?php
    $skip = array('userID', 'pathToReportData', 'dateGenerated',
                  'sortedByWeightFlaggedPostsArray', 'sortedByWeightFlaggedWordsAndFrequencyArray');
    $dates = array('firstPostDate', 'lastPostDate');
    $format = 'Y-m-d';
    foreach($rawAce as $key => $value) {
        if(in_array($key, $skip)) {
            continue;
        } else if(in_array($key, $dates)) {
            
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
        print_r($user);
        print_r($rawAce);
        print_r(date_parse($flaggedPostArray[0]->postData->date)); ?>
    </pre>
</div><!--/#test-output-->

</body>
</html>