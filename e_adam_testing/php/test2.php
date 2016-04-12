<?php
/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/5/2016
 * Time: 11:03 PM
 */

require_once '../../libs/facebook-php-sdk-v4-5.0.0/src/Facebook/autoload.php';

$fb = new Facebook\Facebook([
    'app_id' => '{app-id}',
    'app_secret' => '{app-secret}',
    'default_graph_version' => 'v2.2',
]);

$helper = $fb->getJavaScriptHelper();

try {
    $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

if (! isset($accessToken)) {
    echo 'No cookie set or no OAuth data could be obtained from cookie.';
    exit;
}

// Logged in
echo '<h3>Access Token</h3>';
var_dump($accessToken->getValue());

$_SESSION['fb_access_token'] = (string) $accessToken;

// User is logged in!
// You can redirect them to a members-only page.
//header('Location: https://example.com/members.php');

//<!--//    $reportData = array(-->
//<!--//        'postID'=>array(-->
//<!--//            'postMessage'=>'Facebook post contents',-->
//<!--//            'matchedWords'=>array(-->
//<!--//                'wordBank1'=>array(1,2,3),-->
//<!--//                'wordBank2'=>array(4,5,6),-->
//<!--//                'wordBank3'=>array(7,8,9),-->
//<!--//                'wordBank4'=>array(11,22,33),-->
//<!--//                'wordBank5'=>array(44,55,66)-->
//<!--//            )-->
//<!--//        ),-->
//<!--//        'postID2'=>array(-->
//<!--//            'postMessage'=>'Facebook post contents',-->
//<!--//            'matchedWords'=>array(-->
//<!--//                'wordBank1'=>array(1,2,3),-->
//<!--//                'wordBank2'=>array(4,5,6),-->
//<!--//                'wordBank3'=>array(7,8,9),-->
//<!--//                'wordBank4'=>array(11,22,33),-->
//<!--//                'wordBank5'=>array(44,55,66)-->
//<!--//            )-->
//<!--//        )-->
//<!--//    );-->