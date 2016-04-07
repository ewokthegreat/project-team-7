<?php
/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/5/2016
 * Time: 11:03 PM
 */
define( "__PROJECT_ROOT__", $_SERVER['DOCUMENT_ROOT'] . '/spider');
define( '__FB_API_PATH__', 'libs/facebook-php-sdk-v4-5.0.0/src/Facebook/autoload.php');

require_once __PROJECT_ROOT__ . '/' . __FB_API_PATH__;

$fb = new \Facebook\Facebook([
    'app_id' => '1679655878969496',
    'app_secret' => '74ab0d53fbe6e26d3f001bc7f31cfcea',
    'default_graph_version' => 'v2.5'
]);

$helper = $fb->getJavaScriptHelper();
// checking if access token is not null
try {
    $accessToken = $helper->getAccessToken();
} catch (Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

if (isset($accessToken)) {
    if (isset($_SESSION['facebook_access_token'])) {
        $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
    } else {
        $_SESSION['facebook_access_token'] = (string)$accessToken;
        // OAuth 2.0 client handler
        $oAuth2Client = $fb->getOAuth2Client();
        // Exchanges a short-lived access token for a long-lived one
        $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
        $_SESSION['facebook_access_token'] = (string)$longLivedAccessToken;
        $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
    }
}

echo $accessToken;

print_r($_SERVER);
print_r($_REQUEST);
$rawPostBody = file_get_contents('php://input');
$postData = json_decode($rawPostBody, true);
$postData['name'] = 'JOHN';
print_r($postData);
?>
