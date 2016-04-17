<?php
/**
 * Created by PhpStorm.
 * User: ewokthegreat
 * Date: 4/9/2016
 * Time: 8:07 PM
 */

class AppEngine
{
    /**
     * AppEngine constructor.
     */
    public function __construct() {
        $this->db = new DatabaseConnector();
        $this->fb = $this->createServiceClass();
        $this->token = $this->getAccessToken();
        $this->user = $this->generateUserData();
    }

    /**
     * @param $requestString
     * @param $callback
     */
    public function makeGraphRequest($requestString, $callback) {
        $fb = $this->fb;

        try {
            $response = $fb->get($requestString);
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $callback($response);
    }

    /**
     * @param $data
     */
    public function writeGraphResponseToDiskAsJSON($data) {
        $this->makeGraphRequest('/me?fields=id', function($response) use($data) {
            $user = json_decode($response->getGraphUser());
            $userID = $user->id;

            $dateString = date('y.m.d.G.i.s__');
            $pathToUserData = __RAW_USER_DATA__ . '/' . $userID;
            $userDataFileName = $dateString . $userID . '.json';

            echo "'\n'" . 'PATHTOUSERDATA = ' . $pathToUserData . "'\n'";
            echo "'\n'" . 'USERDATAFILENAME = ' . $userDataFileName . "'\n'";

            if(!file_exists($pathToUserData)) {
                mkdir($pathToUserData);
                file_put_contents($pathToUserData . '/' . $userDataFileName, json_encode($data));
            } else {
                echo 'Directory already exists, attempting to write file!';
                if(!file_exists($pathToUserData . '/' . $userDataFileName)) {
                    file_put_contents($pathToUserData . '/' . $userDataFileName, json_encode($data));
                } else {
                    echo 'A file with that name already exists!';
                }

            }
        });
    }

    private $fb;
    private $id = '1679655878969496';
    private $secret = '74ab0d53fbe6e26d3f001bc7f31cfcea';
    private $version = 'v2.5';
    private $token;
    private $user;
    private $db;


    /**
     * @return \Facebook\Facebook
     */
    public function getFb()
    {
        return $this->fb;
    }

    /**
     * @param \Facebook\Facebook $fb
     */
    public function setFb($fb)
    {
        $this->fb = $fb;
    }

    /**
     * @return DatabaseConnector
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @param DatabaseConnector $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return \Facebook\Facebook
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param \Facebook\Facebook $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return \Facebook\Facebook
     */
    private function createServiceClass() {
        $fb = new \Facebook\Facebook([
            'app_id' => $this->id,
            'app_secret' => $this->secret,
            'default_graph_version' => $this->version
        ]);

        return $fb;
    }

    /**
     * @param $fb - an authorized instance of our application
     * @return mixed - an object containing the user data we need
     */
    private function generateUserData() {
        $db = $this->db;

        $requestString = '/me?fields=id,first_name,last_name,email,link,picture.width(80)';
        $this->makeGraphRequest($requestString, function($response) use($db) {
            $user = json_decode($response->getGraphUser());
            $applicantID = $user->id;
            $fbAuthToken = $this->fb->getDefaultAccessToken()->getValue();
            $firstName = $user->first_name;
            $lastName = $user->last_name;
            $email = $user->email;
            $profileLink = $user->link;
            $password = '1qaz2wsx!QAZ@WSX';
            $isAdmin = FALSE;
            $profilePicture = $user->picture->url;
            $userData = new Applicant($applicantID, $fbAuthToken, $firstName, $lastName,
                $email, $profileLink, $password, $isAdmin, $profilePicture);

            $db->insert($userData);
        });
    }

    /**
     * @return \Facebook\Facebook
     */
    private function getAccessToken() {
        $fb = $this->fb;
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

        return $_SESSION['facebook_access_token'];
    }

}