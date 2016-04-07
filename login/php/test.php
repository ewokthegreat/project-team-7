<?php
/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/5/2016
 * Time: 11:03 PM
 */
print_r($_SERVER);
print_r($_POST);
print_r($_GET);
print_r($_REQUEST);
$rawPostBody = file_get_contents('php://input');
$postData = json_decode($rawPostBody, true);
$postData['name'] = 'JOHN';
print_r($postData);
?>
