<?php
/**
 * Created by IntelliJ IDEA.
 * User: Clark
 * Date: 3/29/2016
 * Time: 10:30 PM
 */

require_once '../libs/facebook-php-sdk-v4-5.0.0/src/Facebook/autoload.php';
require_once 'WordBank.php';

$wordBank = WordBankHandler::getWordBank('SpiderWordBank.csv');
$totalPosts = getAllPosts();
flagPosts($wordBank, $totalPosts);

/**TODO:
 *
 * Need to get new permission from app: Status & Review -> Items in Review -> add
 * items to submission -> user_posts
 *
 */


/**
 * @return array
 *
 * Gets all posts from a user's feed.
 */
function getAllPosts()
{
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

    $total_posts = array();
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

        // getting all posts from timeline
        try {
            $posts_request = $fb->get('/me/posts?limit=500');
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $posts_response = $posts_request->getGraphEdge();
        if ($fb->next($posts_response)) {
            $response_array = $posts_response->asArray();
            $total_posts = array_merge($total_posts, $response_array);
            while ($posts_response = $fb->next($posts_response)) {
                $response_array = $posts_response->asArray();
                $total_posts = array_merge($total_posts, $response_array);
            }
            //for testing if we're getting data
//            print_r($total_posts);
//            foreach ($total_posts as $key){
//                echo $key['message'];
//            }
        } else {
            $posts_response = $posts_request->getGraphEdge()->asArray();
            print_r($posts_response);
        }
    }
    return $total_posts;

}

/**
 * @param array $wordBank
 * @param array $total_posts
 * @return array
 *
 * Start of algorithm. This function matches the wordbank to each post's message and
 * adds that post id and message to a new array.
 */
function flagPosts($wordBank = array(), $total_posts = array())
{
    $flaggedPostIDs = array();
    foreach ($total_posts as $currentPost) {
        foreach ($wordBank as $currentWord) {
            if (strpos(strtolower($currentPost['message']), strtolower($currentWord)) !== FALSE) {
                $flaggedPostIDs[$currentPost['id']] = $currentPost['message'];
            }
        }
    }
    print_r($flaggedPostIDs);
    return $flaggedPostIDs;
}

?>