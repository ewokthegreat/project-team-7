<?php
/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/9/2016
 * Time: 9:23 PM
 * Upder: NOW
 */
ini_set('display_errors',1);
error_reporting(E_ALL);

define('__PROJECT_ROOT__', $_SERVER['DOCUMENT_ROOT']);
define('__FB_API_PATH__', '/libs/facebook-php-sdk-v4-5.0.0/src/Facebook/autoload.php');
define('__RAW_USER_DATA__', __PROJECT_ROOT__ . '/.raw_user_data');

require_once __PROJECT_ROOT__ .  __FB_API_PATH__;
require_once 'DBObject.php';
require_once 'Applicant.php';
require_once 'Scan.php';
require_once 'DatabaseConnector.php';
require_once 'AppEngine.php';
require_once 'ReportGenerator.php';

function trace($msg) {
    echo "<pre>****$msg*****</pre>";
}

$app = new AppEngine();
$db = $app->getDb();
$fb = $app->getFb();

getAllPosts($app);

function getAllPosts($app) {
    $app->makeGraphRequest('/me/posts?limit=500', function($response) use($app) {
        $fb = $app->getFb();
        $posts_response = $response->getGraphEdge();
        $total_posts = array();

        if ($fb->next($posts_response)) {
            $response_array = $posts_response->asArray();
            $total_posts = array_merge($total_posts, $response_array);

            while ($posts_response = $fb->next($posts_response)) {
                $response_array = $posts_response->asArray();
                $total_posts = array_merge($total_posts, $response_array);
            }

        } else {
            $total_posts = $response->getGraphEdge();
//            $posts_response = $response->getGraphEdge()->asArray();
//            echo json_encode($posts_response);
        }
        $app->writeGraphResponseToDiskAsJSON($total_posts);
    });
}