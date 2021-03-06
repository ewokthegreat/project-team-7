<?php
/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/17/2016
 * Time: 7:19 PM
 */
ini_set('display_errors',1);
error_reporting(E_ALL);

define('__PROJECT_ROOT__', $_SERVER['DOCUMENT_ROOT']);
define('__DICTIONARY_DATA__', __PROJECT_ROOT__ . '/' . 'dictionaries');
define('__PHP_SCRIPTS__', __PROJECT_ROOT__ . '/' . 'php');
define('__USER_DATA__', __PROJECT_ROOT__ . '/' . '.raw_user_data');

include_once __PHP_SCRIPTS__ . '/' . 'DBObject.php';
include_once __PHP_SCRIPTS__ . '/' . 'PostData.php';
include_once __PHP_SCRIPTS__ . '/' . 'DictionaryData.php';
include_once __PHP_SCRIPTS__ . '/' . 'ReportGenerator.php';
include_once __PHP_SCRIPTS__ . '/' . 'Scan.php';
include_once __PHP_SCRIPTS__ . '/' . 'Applicant.php';
include_once __PHP_SCRIPTS__ . '/' . 'DatabaseConnector.php';

$postData = file_get_contents('php://input');
$postDataArray =  json_decode($postData);
$userID = $postDataArray->id;
$db = new DatabaseConnector();
$scanData = $db->selectAllScansFromApplicant($userID);

$obj = new stdClass();
$obj->userID = $userID;
$obj->scanData = $scanData;

sendData($obj);

function sendData($data) {
    print_r(json_encode($data));
}

