<?php
/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/9/2016
 * Time: 9:23 PM
 */
ini_set('display_errors',1);
error_reporting(E_ALL);

define('__PROJECT_ROOT__', $_SERVER['DOCUMENT_ROOT']);
define('__FB_API_PATH__', '/libs/facebook-php-sdk-v4-5.0.0/src/Facebook/autoload.php');
define('__RAW_USER_DATA__', __PROJECT_ROOT__ . '/.raw_user_data');

require_once __PROJECT_ROOT__ .  __FB_API_PATH__;
require_once 'WordBank.php';
require_once 'User.php';
require_once 'Report.php';
require_once 'DatabaseConnector.php';
require_once 'AppEngine.php';

$app = new AppEngine();
$db = $app->getDb();
$fb = $app->getFb();

print_r($fb->get('/me/posts?limit=500'));