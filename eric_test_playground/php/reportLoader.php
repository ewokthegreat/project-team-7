<?php
/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/20/2016
 * Time: 2:50 PM
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


function trace($msg) {
    echo "'\n'****$msg*****'\n'";
}

$data = file_get_contents('php://input');
$dataArray =  json_decode($data);
//print_r($dataArray);