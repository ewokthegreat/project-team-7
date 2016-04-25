<?php
/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/20/2016
 * Time: 2:50 PM
 */
ini_set('display_errors',1);
error_reporting(E_ALL);

$data = file_get_contents('php://input');
$dataArray =  json_decode($data);
$aceFile = file_get_contents($dataArray->path);
print_r($aceFile);