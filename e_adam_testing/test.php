<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

include_once __DIR__ . '/php/Applicant.php';
include_once __DIR__ . '/php/Scan.php';
include_once __DIR__ . '/php/DatabaseConnector.php';

$db = new DatabaseConnector(NULL, NULL);
$users = $db->selectAllUsers();

$user = $db->selectUser(1);

echo '<pre>';

var_dump($users);
var_dump($user);

echo '</pre>';